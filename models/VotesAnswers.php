<?php

namespace app\models;

use app\helpers\Normalize;
use app\models\queries\VotesAnswersQuery;
use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "{{%votes_answers}}".
 *
 * @property integer $id
 * @property integer $vote_id
 * @property integer $variant_id
 * @property string $user_ip
 * @property integer $created
 *
 * @property Votes $vote
 * @property VotesVariants $variant
 */
class VotesAnswers extends ActiveRecord
{
    public static function tableName()
    {
        return '{{%votes_answers}}';
    }

    public function rules()
    {
        return [
            [['vote_id', 'variant_id', 'user_ip', 'created'], 'required'],
            [['vote_id', 'variant_id', 'created'], 'integer'],
            [['user_ip'], 'string', 'max' => 20],
        ];
    }
    
    public function getVote()
    {
        return $this->hasOne(Votes::className(), ['id' => 'vote_id']);
    }
    
    public function getVariant()
    {
        return $this->hasOne(VotesVariants::className(), ['id' => 'variant_id']);
    }

    public function attributeLabels()
    {
        return Normalize::withCommonLabels([
            'vote_id' => 'Голосование',
            'variant_id' => 'Вариант',
            'user_ip' => 'IP',
        ]);
    }

    public static function find()
    {
        return new VotesAnswersQuery(get_called_class());
    }
}
