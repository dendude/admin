<?php

namespace app\models;

use app\helpers\Normalize;
use app\models\queries\ProjectsQuery;
use app\models\traits\GetManagerTrait;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;
use yii\web\Cookie;

/**
 * This is the model class for table "{{%projects}}".
 *
 * @property integer $id
 * @property integer $manager_id
 * @property string $site_name
 * @property string $site_url
 * @property string $site_icon
 * @property string $mark_text
 * @property integer $created
 * @property integer $modified
 * @property integer $status
 * @property integer $ordering
 */
class Projects extends ActiveRecord
{
    use GetManagerTrait;
    
    const SESSION_KEY = 'project_id';
    
    const MAKEBODY_ID = 1;
    const RAMENSKOE_ID = 2;
    const TCGROUP_ID = 3;
    
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%projects}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manager_id', 'created', 'site_name', 'site_url', 'site_icon'], 'required'],
            
            [['manager_id', 'created', 'modified', 'status', 'ordering'], 'integer'],
            [['manager_id', 'created', 'modified', 'status', 'ordering'], 'default', 'value' => 0],
            
            [['site_name', 'site_url', 'site_icon'], 'string', 'max' => 100],
            [['mark_text'], 'string', 'max' => 50],
            [['site_url'], 'url'],
        ];
    }
    
    public function beforeValidate()
    {
        if ($this->isNewRecord) {
            $this->created = time();
        } else {
            $this->modified = time();
        }
    
        $this->manager_id = Yii::$app->user->id;
        
        return parent::beforeValidate();
    }
    
    // установка текущего управляемого проекта
    public function setCurrent() {
        $cookies = Yii::$app->response->cookies;
        $cookies->add(new Cookie([
            'name' => self::SESSION_KEY,
            'value' => $this->id
        ]));
    }
    
    /**
     * @return integer
     */
    public static function getCurrent() {
        $cookies = Yii::$app->request->cookies;
        return (int)$cookies->getValue(self::SESSION_KEY);
    }
    public static function getCurrentModel() {
        return self::findOne(self::getCurrent());
    }
    public static function isCurrent($project_id) {
        return ($project_id == self::getCurrent());
    }
    
    public static function hasNewsSections()
    {
        return (self::getCurrent() == self::MAKEBODY_ID);
    }
    
    public static function getFilterList() {
        $list = self::find()->active()->ordering()->all();
        return $list ? ArrayHelper::map($list, 'id', 'site_name') : [];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return Normalize::withCommonLabels([
            'site_name' => 'Название',
            'site_url' => 'Ссылка на сайт',
            'site_icon' => 'Класс иконки',
            'mark_text' => 'Водяной текст на фото',
        ]);
    }

    /**
     * @inheritdoc
     * @return \app\models\queries\ProjectsQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new ProjectsQuery(get_called_class());
    }
}
