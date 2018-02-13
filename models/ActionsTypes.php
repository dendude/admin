<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\helpers\Normalize;
use app\models\queries\ActionsTypesQuery;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "{{%actions_types}}".
 *
 * @property integer $id
 * @property integer $manager_id
 * @property string $name
 * @property string $comment
 * @property integer $is_stat
 * @property integer $created
 * @property integer $modified
 */
class ActionsTypes extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%actions_types}}';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['manager_id', 'is_stat', 'created', 'modified'], 'integer'],
            [['manager_id', 'is_stat', 'created', 'modified'], 'default', 'value' => 0],
            
            [['name'], 'string', 'max' => 100],
            [['comment'], 'string', 'max' => 500],
        ];
    }
    
    public static function getFilterList()
    {
        $list = self::find()->all();
        return $list ? ArrayHelper::map($list, 'id', 'name') : [];
    }

    public function attributeLabels()
    {
        return Normalize::withCommonLabels([
            'name' => 'Название',
            'comment' => 'Комментарий',
            'is_stat' => 'Учет',
        ]);
    }

    public static function find()
    {
        return new ActionsTypesQuery(get_called_class());
    }
}
