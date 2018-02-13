<?php

namespace app\models;

use app\helpers\Normalize;
use app\models\queries\VotesVariantsQuery;
use app\models\traits\BeforeValidateTrait;
use app\models\traits\GetManagerTrait;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%votes_variants}}".
 *
 * @property integer $id
 * @property integer $manager_id
 * @property integer $vote_id
 * @property string $title
 * @property integer $created
 * @property integer $modified
 * @property integer $ordering
 * @property integer $status
 *
 * @property Votes $vote
 */
class VotesVariants extends ActiveRecord
{
    use BeforeValidateTrait;
    use GetManagerTrait;
    
    public static function tableName()
    {
        return '{{%votes_variants}}';
    }

    public function rules()
    {
        return [
            [['manager_id', 'project_id', 'vote_id', 'title', 'created'], 'required'],

            [['modified', 'ordering', 'status'], 'integer'],
            [['modified', 'ordering', 'status'], 'default', 'value' => 0],
            
            [['title'], 'string', 'max' => 100],
        ];
    }
    
    public function beforeValidate()
    {
        $this->beforeValidateTrait();
        
        return parent::beforeValidate();
    }
    
    public function getVote() {
        return $this->hasOne(Votes::className(), ['id' => 'vote_id']);
    }
    
    public function attributeLabels()
    {
        return Normalize::withCommonLabels([
            'vote_id' => 'Голосование',
            'title' => 'Вариант ответа',
        ]);
    }

    public static function find()
    {
        return new VotesVariantsQuery(get_called_class());
    }
}
