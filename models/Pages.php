<?php

namespace app\models;

use app\components\simple_html_dom;
use app\helpers\Normalize;
use app\models\forms\UploadForm;
use app\models\queries\PagesQuery;
use app\models\traits\BeforeValidateTrait;
use app\models\traits\GetManagerTrait;
use PHPHtmlParser\Dom;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\helpers\Url;

/**
 * This is the model class for table "{{%pages}}".
 *
 * @property integer $id
 * @property integer $manager_id
 * @property integer $project_id
 * @property string $title
 * @property string $alias
 * @property string $meta_t
 * @property string $meta_d
 * @property string $meta_k
 * @property string $content
 * @property string $bread_name
 * @property string $breads_top
 * @property string $breads_bottom
 *
 * @property string $video_src
 * @property string $video_preview
 *
 * @property integer $is_sitemap
 * @property integer $is_shared
 * @property integer $created
 * @property integer $modified
 * @property integer $status
 *
 * @property Users $manager
 */
class Pages extends ActiveRecord
{
    public $breads_top_arr = [];
    public $breads_bottom_arr = [];
    // итоговая ссылка
    public $full_url;
    
    use GetManagerTrait;
    use BeforeValidateTrait;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%pages}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manager_id', 'project_id', 'created', 'title', 'alias', 'content'], 'required'],
            
            // уникальность по алиасу и проекту
            ['alias', 'unique', 'targetAttribute' => ['alias', 'project_id']],
            
            [['manager_id', 'project_id', 'created', 'modified', 'status'], 'integer'],
            [['manager_id', 'project_id', 'created', 'modified', 'status'], 'default', 'value' => 0],
            
            [['is_sitemap', 'is_shared'], 'boolean'],
            
            [['title', 'alias', 'meta_t', 'meta_d', 'meta_k', 'bread_name', 'video_src', 'video_preview'], 'string', 'max' => 250],
            [['content', 'breads_top', 'breads_bottom'], 'string'],

            [['breads_top_arr', 'breads_bottom_arr', 'full_url'], 'safe'],
        ];
    }
    
    public static function getFilterList() {
        $list = self::find()->select('id, title')->managed()->byCurrentProject()->ordering()->all();
        return $list ? ArrayHelper::map($list, 'id', 'title') : [];
    }
    
    public function beforeValidate()
    {
        $this->beforeValidateTrait();
        
        $this->modified = time();
        $this->alias = trim($this->alias, '/');
        
        if (is_array($this->breads_top_arr) && count($this->breads_top_arr)) {
            $this->breads_top = Json::encode($this->breads_top_arr);
        } else {
            $this->breads_top = '';
        }
    
        if (is_array($this->breads_bottom_arr) && count($this->breads_bottom_arr)) {
            $this->breads_bottom = Json::encode($this->breads_bottom_arr);
        } else {
            $this->breads_bottom = '';
        }
        
        if ($this->video_src) {
            $parts = explode('?v=', $this->video_src);
            if (count($parts) == 2) {
                // https://www.youtube.com/watch?v=KlC94cCzliI
                $video_code = $parts[1];
            } else {
                // https://youtu.be/KlC94cCzliI
                $video_code = array_pop(explode('/', $this->video_src));
            }
            
            if (!empty($video_code)) {
                $this->video_preview = "//img.youtube.com/vi/{$video_code}/mqdefault.jpg";
            }
        }
        
        return parent::beforeValidate();
    }
    
    public function afterFind()
    {
        if ($this->breads_top) $this->breads_top_arr = Json::decode($this->breads_top);
        if ($this->breads_bottom) $this->breads_bottom_arr = Json::decode($this->breads_bottom);

        $project = Projects::getCurrentModel();
        $this->full_url = $project->site_url . ($this->alias == 'index' ? '' : Url::to(["/{$this->alias}"]));

        parent::afterFind();
    }
    
    public function beforeSave($insert)
    {
        $this->content = str_replace(' style=""', '', $this->content);
        return parent::beforeSave($insert);
    }
    
    public function save($runValidation = true, $attributeNames = null)
    {
        $is_new = $this->isNewRecord;
        $saved = parent::save($runValidation, $attributeNames);
        
        if ($saved) {
            $type_id = $is_new ? 1 : 2;
            Actions::add($type_id, [
                'id' => $this->id,
                'title' => $this->title,
                'alias' => $this->alias
            ]);
        }
        
        return $saved;
    }
    
    public function afterDelete()
    {
        $dom = new Dom();
        $dom->loadStr($this->content, []);
        if ($images = $dom->find('img')) {
            // удаляем фотографии
            foreach ($images AS $img) UploadForm::remove($img->src, UploadForm::TYPE_PAGES);
        }
        if ($links = $dom->find('a')) {
            // удаляем документы
            foreach ($links AS $u) UploadForm::removeDoc($u->href);
        }
        
        parent::afterDelete();
    }
    
    public function attributeLabels()
    {
        return Normalize::withCommonLabels([
            'title' => 'Заголовок (H1)',
            'content' => 'Контент страницы',
            'bread_name' => 'Название хлебной крошки',
            'breads_top' => 'Хлебные крошки навигации',
            'breads_bottom' => 'Дополнительные хлебные крошки',
            
            'is_sitemap' => 'Добавить страницу в карту сайта',
            'is_shared' => 'Подключить блок "Поделиться"',
            'video_src' => 'Код видео для Видеоновостей',
        ]);
    }

    public static function find()
    {
        return new PagesQuery(get_called_class());
    }
}