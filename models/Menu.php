<?php
namespace app\models;

use app\helpers\Normalize;
use app\helpers\Statuses;
use app\models\queries\MenuQuery;
use app\models\traits\BeforeValidateTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%menu}}".
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $manager_id
 * @property integer $parent_id
 * @property integer $page_id
 * @property integer $gallery_id
 * @property string $name
 * @property string $title
 * @property integer $ordering
 * @property integer $status
 * @property integer $created
 * @property integer $modified
 *
 * @property Menu[] $childs
 * @property Menu $parent
 */
class Menu extends ActiveRecord
{
    use BeforeValidateTrait;
    
    public static function tableName()
    {
        return '{{%menu}}';
    }

    public function rules()
    {
        return [
            [['project_id', 'manager_id', 'name', 'created'], 'required'],

            [['project_id', 'manager_id', 'parent_id', 'page_id', 'gallery_id', 'ordering', 'status', 'created', 'modified'], 'integer'],
            [['project_id', 'manager_id', 'parent_id', 'page_id', 'gallery_id', 'ordering', 'status', 'created', 'modified'], 'default', 'value' => 0],

            [['name','title'], 'string', 'max' => 100],
            [['name','title'], 'default', 'value' => ''],
        ];
    }

    public function getPage() {
        return $this->hasOne(Pages::className(), ['id' => 'page_id'])->andWhere(['project_id' => Projects::getCurrent()]);
    }
    
    public function getGallery() {
        return $this->hasOne(Pages::className(), ['id' => 'gallery_id'])->andWhere(['project_id' => Projects::getCurrent()]);
    }
    
    public function getParent() {
        return $this->hasOne(self::className(), ['id' => 'parent_id']);
    }
    
    public function beforeValidate()
    {
        $this->beforeValidateTrait();
        
        return parent::beforeValidate();
    }
    
    
    public function beforeSave($insert)
    {
        $is_new_ordering = false;
        
        if ($this->isNewRecord) {
            // новый пункт
            $is_new_ordering = true;
        } else {
            // пункт перенесен в другой раздел
            $self = self::findOne($this->id);
            if ($self->parent_id != $this->parent_id) $is_new_ordering = true;
        }
        
        if ($is_new_ordering) {
            // выбираем максимальное значение порядка
            $max_ord = (int) self::find()->byParent($this->parent_id)->byCurrentProject()->max('ordering');
            $this->ordering = ($max_ord + 1);
        }
        
        // выбрана галерея - убираем страницу
        if (!empty($this->page_id)) $this->gallery_id = 0;
        if (!empty($this->gallery_id)) $this->page_id = 0;

        return parent::beforeSave($insert);
    }

    public static function getFilterList($parent_id = 0, $level = 0) {
        $list = [];
        $models = self::find()->byParent($parent_id)->byCurrentProject()->ordering()->all();
        
        if ($models) {
            foreach ($models AS $item) {
                $list[$item->id] = str_repeat('&nbsp;', ($level * 2)) . $item->name;
                if (self::find()->byParent($item->id)->count()) {
                    $list += self::getFilterList($item->id, $level + 1);
                }
            }
        }

        return $list;
    }

    public function attributeLabels() {
        return Normalize::withCommonLabels([
            'name' => 'Название',
            'title' => 'Подсказка',
            'parent_id' => 'Родительский пункт меню',
            'page_id' => 'Прикрепленная страница',
            'gallery_id' => 'Прикрепленная галерея',
        ]);
    }
    
    public static function find() {
        return new MenuQuery(get_called_class());
    }
}
