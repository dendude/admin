<?php
namespace app\models;

use app\components\simple_html_dom;
use app\helpers\Normalize;
use app\models\forms\UploadForm;
use app\models\queries\NewsQuery;
use app\models\traits\BeforeValidateTrait;
use app\models\traits\GetManagerTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%news}}".
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $manager_id
 * @property integer $section_id
 * @property string $photo
 * @property string $title
 * @property string $title_menu
 * @property string $bread_name
 * @property string $alias
 * @property string $about
 * @property string $meta_t
 * @property string $meta_d
 * @property string $meta_k
 * @property string $content
 * @property integer $created
 * @property integer $modified
 * @property integer $pub_date
 * @property integer $status
 * @property integer $views
 * @property integer $ordering
 * @property integer $is_slider
 *
 * @property NewsSections $section
 */
class News extends ActiveRecord
{
    use GetManagerTrait;
    use BeforeValidateTrait;
    
    public $pub_date_str;
    
    public static function tableName()
    {
        return '{{%news}}';
    }

    public function rules()
    {
        return [
            ['photo', 'required', 'message' => 'Необходимо загрузить фото новости'],
            [['title', 'alias', 'bread_name', 'about', 'title_menu', 'created', 'content'], 'required'],
            
            [['project_id', 'manager_id', 'section_id', 'created', 'modified', 'status', 'views', 'ordering'], 'integer'],
            [['photo', 'title', 'title_menu', 'bread_name', 'alias', 'meta_t', 'meta_d', 'meta_k'], 'string', 'max' => 250],
            ['about', 'string', 'max' => 500],
            ['is_slider', 'boolean'],
            ['content', 'string'],

            [['pub_date', 'pub_date_str'], 'required'],
            ['pub_date', 'date', 'format' => 'yyyy-MM-dd'],
            ['pub_date_str', 'date', 'format' => 'dd.MM.yyyy'],
        ];
    }
    
    public function getManager() {
        return $this->hasOne(Users::className(), ['id' => 'manager_id']);
    }
    
    public function getSection() {
        return $this->hasOne(NewsSections::className(), ['id' => 'section_id']);
    }
    
    public function afterFind()
    {
        parent::afterFind();
        $this->pub_date_str = date('d.m.Y', strtotime($this->pub_date));
    }
    
    
    public function beforeValidate()
    {
        $this->beforeValidateTrait();
        $this->photo = array_pop(explode('/', $this->photo));
        
        if ($this->isNewRecord) {
            $this->pub_date = date('Y-m-d', $this->created);
        } else {
            $this->pub_date = date('Y-m-d', strtotime($this->pub_date_str));
        }
        
        return parent::beforeValidate();
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
            $type_id = $is_new ? 3 : 4;
            Actions::add($type_id, [
                'id' => $this->id,
                'title' => $this->title,
                'alias' => $this->alias
            ]);
        }
        
        return $saved;
    }
    
    public function attributeLabels()
    {
        return Normalize::withCommonLabels([
            'about' => 'Краткое описание',
            'title' => 'Заголовок',
            'title_menu' => 'Название в меню',
            'bread_name' => 'Текст хлебной крошки',
            'is_slider' => 'Показывать новость в слайдере',
            'pub_date' => 'Дата новости',
            'pub_date_str' => 'Дата новости',
        ]);
    }

    public static function find()
    {
        return new NewsQuery(get_called_class());
    }
}
