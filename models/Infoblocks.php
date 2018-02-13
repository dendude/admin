<?php
namespace app\models;

use app\helpers\Statuses;
use app\models\traits\BeforeValidateTrait;
use app\models\traits\GetManagerTrait;
use Yii;
use yii\db\ActiveRecord;
use app\helpers\Normalize;
use app\models\queries\InfoblocksQuery;
use yii\helpers\Json;

/**
 * This is the model class for table "{{%infoblocks}}".
 *
 * @property integer $id
 * @property integer $manager_id
 * @property integer $project_id
 * @property string $title
 * @property string $pages
 * @property integer $created
 * @property integer $modified
 * @property integer $status
 *
 * @property Users $manager
 */
class Infoblocks extends ActiveRecord
{
    use GetManagerTrait;
    use BeforeValidateTrait;
    
    public $pages_arr = [];
    
    public static function tableName()
    {
        return '{{%infoblocks}}';
    }

    public function rules()
    {
        return [
            [['pages', 'manager_id', 'project_id', 'created'], 'required'],
            
            [['manager_id', 'project_id', 'created', 'modified', 'status'], 'integer'],
            [['modified', 'status'], 'default', 'value' => 0],
            
            [['title'], 'string', 'max' => 100],
            [['pages'], 'string'],

            [['pages_arr'], 'safe'],
        ];
    }
    
    public function getManager() {
        return $this->hasOne(Users::className(), ['id' => 'manager_id']);
    }
    
    public function beforeValidate()
    {
        $this->beforeValidateTrait();
        
        if (!empty($this->pages_arr) && is_array($this->pages_arr)) {
            
            $tmpPages = [];
            
            foreach ($this->pages_arr AS $ik => $iv) {
                if (empty($this->pages_arr[$ik]['page_id']) || empty($this->pages_arr[$ik]['name'])) {
                    unset($this->pages_arr[$ik]);
                } else {
                    $tmpPages[] = $this->pages_arr[$ik]['page_id'];
                }
            }
            $this->pages = count($this->pages_arr) ? Json::encode($this->pages_arr) : '';
        } else {
            $this->pages = '';
        }
        
        return parent::beforeValidate();
    }
    
    public function afterFind()
    {
        if ($this->pages) $this->pages_arr = Json::decode($this->pages);
        
        parent::afterFind();
    }
    
    public function getPagesInfo()
    {
        $pages = [];
        foreach ($this->pages_arr AS $iv) {
            $p = Pages::findOne($iv['page_id']);
            if ($p) $pages[] = $p->title;
        }
        
        return $pages;
    }
    
    public function deleteFromPages()
    {
        /** @var $pages Pages[] */
        $pages = Pages::find()->all();
        foreach ($pages AS $p) {
            $p->updateAttributes([
                // удаляем инфоблоки со страниц проектов
                'content' => str_replace("{infoblock-{$this->id}}", '', $p->content)
            ]);
        }
    }
    
    public function attributeLabels()
    {
        return Normalize::withCommonLabels([
            'title' => 'Заголовок',
            'pages' => 'Страницы',
        ]);
    }

    public static function find()
    {
        return new InfoblocksQuery(get_called_class());
    }
}
