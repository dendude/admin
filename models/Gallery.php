<?php

namespace app\models;

use app\helpers\Normalize;
use app\helpers\Statuses;
use app\models\forms\UploadForm;
use app\models\queries\GalleryQuery;
use app\models\traits\BeforeValidateTrait;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%gallery}}".
 *
 * @property integer $id
 * @property integer $manager_id
 * @property integer $project_id
 * @property integer $parent_id
 * @property string $name
 * @property string $alias
 * @property string $bread_name
 * @property string $meta_t
 * @property string $meta_d
 * @property string $meta_k
 * @property string $content
 * @property string $images
 * @property integer $created
 * @property integer $modified
 * @property integer $ordering
 * @property integer $status
 * @property integer $is_gallery
 *
 * @property Gallery $parent
 * @property Gallery[] $childs
 * @property Gallery[] $childsActive
 */
class Gallery extends ActiveRecord
{
    use BeforeValidateTrait;
    
    const SCENARIO_EDIT = 'edit';

    public $images_f = []; // names
    public $images_o = []; // names old
    public $images_t = []; // titles
    public $images_a = []; // alts
    public $images_i = []; // album ids
    
    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_EDIT] = $scenarios[self::SCENARIO_DEFAULT];
        
        return $scenarios;
    }
    
    public static function tableName()
    {
        return '{{%gallery}}';
    }

    public function rules()
    {
        return [
            [['manager_id', 'project_id', 'created'], 'required'],
            [['name', 'alias', 'bread_name'], 'required'],
            
            [['manager_id', 'project_id', 'parent_id', 'created', 'modified', 'ordering', 'status', 'is_gallery'], 'integer'],
            [['manager_id', 'project_id', 'parent_id', 'created', 'modified', 'ordering', 'status', 'is_gallery'], 'default', 'value' => 0],
                        
            [['name', 'alias', 'bread_name'], 'string', 'max' => 100],
            [['meta_t', 'meta_d', 'meta_k'], 'string', 'max' => 250],

            [['content', 'images'], 'string'],
            [['content', 'images'], 'default', 'value' => ''],
            
            ['images_f', 'each', 'rule' => ['string']],
            ['images_o', 'each', 'rule' => ['string']],
            ['images_t', 'each', 'rule' => ['string']],
            ['images_a', 'each', 'rule' => ['string']],
            ['images_i', 'each', 'rule' => ['integer']],
        ];
    }
    
    public static function getFullName($category_id, $separator = ' &raquo; ') {
        $result = [];
        $cat_id = explode('_', $category_id);
        foreach ($cat_id AS $cid) {
            $m = self::findOne($cid);
            if ($m) $result[] = $m->name;
        }
        
        return implode($separator, array_reverse($result));
    }
    
    public function getParent() {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }
    
    public function getChilds() {
        return $this->hasMany(self::className(), ['parent_id' => 'id'])->orderBy(['ordering' => SORT_ASC]);
    }
    
    public function getChildsActive() {
        return $this->hasMany(self::className(), ['parent_id' => 'id'])->andWhere([
            'status' => Statuses::STATUS_ACTIVE
        ])->orderBy(['ordering' => SORT_ASC]);
    }
    
    public static function getFilterList($parent_id = 0, $level = 0, $sep = '&nbsp;', $rep = 5) {
        $list = [];
        
        foreach (self::find()->byParent($parent_id)->manage()->ordering()->all() AS $menu_item) {
            $list[$menu_item->id] = str_repeat($sep, ($level * $rep)) . $menu_item->name;
            if ($menu_item->childs) $list += self::getFilterList($menu_item->id, ($level + 1), $sep, $rep);
        }
        
        return $list;
    }
    
    public function getUrl($schema = null) {
        return Url::to(["/site/category", 'alias' => $this->alias], $schema);
    }
    
    public function beforeValidate()
    {
        $this->beforeValidateTrait();

        if ($this->images_f) {
            
            // для учета удаленных фото
            $images_f = [];
            $images_t = [];
            $images_a = [];
            
            foreach ($this->images_f AS $fk => $fv) {
                if (empty($fv)) continue;

                // собираем данные о фото
                $images_f[] = array_pop(explode('/', $fv));
                $images_t[] = isset($this->images_t[$fk]) ? $this->images_t[$fk] : '';
                $images_a[] = isset($this->images_a[$fk]) ? $this->images_a[$fk] : '';
            }
            
            // обновляем данные о фото
            $this->images = Json::encode([
                'f' => $images_f,
                't' => $images_t,
                'a' => $images_a,
            ]);
        } else {
            $this->images = null;
        }
        
        return parent::beforeValidate();
    }
    
    public function beforeSave($insert)
    {
        $is_max_ordering = false;
        if ($this->isNewRecord) {
            // выбираем максимальное значение порядка
            $is_max_ordering = true;
        } else {
            $self = self::findOne($this->id);
            if ($self->parent_id != $this->parent_id) {
                // перемещение по родительским категориям
                $is_max_ordering = true;
            }
        }
        
        if ($is_max_ordering) {
            // выбираем максимальное значение порядка
            $max_ord = (int) self::find()->where(['parent_id' => $this->parent_id])->max('ordering');
            $this->ordering = ($max_ord + 1);
        }
        
        return parent::beforeSave($insert);
    }
    
    public function save($runValidation = true, $attributeNames = null)
    {
        $is_new = $this->isNewRecord;
        $saved = parent::save($runValidation, $attributeNames);

        if ($saved) {
            $type_id = $is_new ? 7 : 8;
            Actions::add($type_id, [
                'id' => $this->id,
                'name' => $this->name,
                'alias' => $this->alias,
                'photos' => count($this->images_f),
            ]);
        }
        
        if ($this->scenario == self::SCENARIO_EDIT) return $saved;
    
        $conf = UploadForm::getConfig();
        $project_dir = Yii::getAlias("@app/web/{$conf['symlink']}");
        $upload_dir = "{$project_dir}/{$conf['upload_dir']}";
        $path = $upload_dir . '/' . UploadForm::TYPE_GALLERY;
        
        foreach ($this->images_f AS $k => $v) {
            if (!empty($this->images_f[$k]) && !empty($this->images_o[$k]) && $this->images_f[$k] != $this->images_o[$k]) {
                
                foreach (['', '_lg', '_md', '_sm', '_xs'] AS $size) {
                    // rename files
                    $path_info_old = pathinfo($this->images_o[$k]);
                    $path_info_new = pathinfo($this->images_f[$k]);
                    
                    $old_path = $path . '/' . $path_info_old['filename'] . $size . '.' . $path_info_old['extension'];
                    $new_path = $path . '/' . $path_info_new['filename'] . $size . '.' . $path_info_new['extension'];
    
                    if (file_exists($old_path)) {
                        rename($old_path, $new_path);
                    } else {
                        Yii::error('Try to rename not exists file: ' . $old_path);
                    }
                }
            }
        }

        $this->updateAlbums();
        
        return $saved;
    }

    protected function updateAlbums() {

        $albums = $this->images_i;
        foreach ($albums AS $k => $albumId) {

            if ($albumId != $this->id) {

                $album = self::findOne($albumId);
                $album->images_f[] = $this->images_f[$k];
                $album->images_t[] = $this->images_t[$k];
                $album->images_a[] = $this->images_a[$k];
                $album->updateAttributes([
                    'images' => Json::encode([
                        'f' => $album->images_f,
                        't' => $album->images_t,
                        'a' => $album->images_a,
                    ])
                ]);

                unset($this->images_f[$k]);
                unset($this->images_t[$k]);
                unset($this->images_a[$k]);
                $this->updateAttributes([
                    'images' => Json::encode([
                        'f' => $this->images_f,
                        't' => $this->images_t,
                        'a' => $this->images_a,
                    ])
                ]);
            }
        }
    }
    
    public function afterFind()
    {
        if ($this->images) {
            $images = Json::decode($this->images);
            // we should reset of not consistently keys
            $this->images_o = array_values($images['f']);

            $this->images_f = array_values($images['f']);
            $this->images_t = array_values($images['t']);
            $this->images_a = array_values($images['a']);

            foreach ($this->images_f AS $k => $v) {
                $this->images_i[$k] = $this->id;
            }
        }
        
        parent::afterFind();
    }
    
    public function attributeLabels()
    {
        return Normalize::withCommonLabels([
            'name' => 'Название альбома',
            'parent_id' => 'Родительский альбом',
            'bread_name' => 'Текст хлебной крошки',
            'is_gallery' => 'Отображать на странице фотогалерей',
            'images' => 'Фотографии',
        ]);
    }
    
    public static function find()
    {
        return new GalleryQuery(get_called_class());
    }
}
