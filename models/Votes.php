<?php

namespace app\models;

use app\helpers\Normalize;
use app\helpers\Statuses;
use app\models\queries\VotesAnswersQuery;
use app\models\queries\VotesQuery;
use app\models\traits\BeforeValidateTrait;
use app\models\traits\GetManagerTrait;
use Yii;
use yii\db\ActiveRecord;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%votes}}".
 *
 * @property integer $id
 * @property integer $manager_id
 * @property string $name
 * @property string $title
 * @property string $about
 * @property integer $created
 * @property integer $modified
 * @property integer $status
 */
class Votes extends ActiveRecord
{
    use GetManagerTrait;
    use BeforeValidateTrait;
    
    public static function tableName()
    {
        return '{{%votes}}';
    }

    public function rules()
    {
        return [
            [['manager_id', 'project_id', 'name', 'title', 'created'], 'required'],
            
            [['modified', 'status'], 'integer'],
            [['modified', 'status'], 'default', 'value' => 0],
            
            [['name'], 'string', 'max' => 100],
            [['title'], 'string', 'max' => 50],
            [['about'], 'string', 'max' => 500],
        ];
    }
    
    public function beforeValidate()
    {
        $this->beforeValidateTrait();
        
        return parent::beforeValidate();
    }
    
    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        
        if ($this->status == Statuses::STATUS_ACTIVE) {
            Votes::updateAll(
                ['status' => Statuses::STATUS_DISABLED],
                'id != :id AND status = :status',
                [':id' => $this->id, ':status' => Statuses::STATUS_ACTIVE]
            );
        }
    }
    
    
    public static function getFilterList() {
        $list = self::find()->orderBy('name ASC')->all();
        return $list ? ArrayHelper::map($list, 'id', 'name') : [];
    }
    
    public function deleteFromPages()
    {
        /** @var $pages Pages[] */
        $pages = Pages::find()->all();
        foreach ($pages AS $p) {
            $p->updateAttributes([
                // удаляем инфоблоки со страниц проектов
                'content' => str_replace("{vote-{$this->id}}", '', $p->content)
            ]);
        }
    }
    
    public function attributeLabels()
    {
        return Normalize::withCommonLabels([
            'name' => 'Полное название',
            'title' => 'Название в виджете',
            'about' => 'Краткое описание',
        ]);
    }
    
    public static function find()
    {
        return new VotesQuery(get_called_class());
    }
}
