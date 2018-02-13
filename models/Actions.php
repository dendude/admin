<?php
namespace app\models;

use Yii;
use yii\db\ActiveRecord;
use app\helpers\Normalize;
use app\models\queries\ActionsQuery;
use yii\helpers\Json;

/**
 * This is the model class for table "adm_actions".
 *
 * @property integer $id
 * @property integer $project_id
 * @property integer $manager_id
 * @property integer $object_id
 * @property integer $type_id
 * @property string $info
 * @property integer $created
 *
 * @property Users $manager
 * @property ActionsTypes $type
 */
class Actions extends ActiveRecord
{
    public static function tableName()
    {
        return 'adm_actions';
    }

    public function rules()
    {
        return [
            [['project_id', 'manager_id', 'object_id', 'type_id', 'created'], 'integer'],
            [['project_id', 'manager_id', 'object_id', 'type_id', 'created'], 'default', 'value' => 0],
            
            [['info'], 'string', 'max' => 1000],
        ];
    }
    
    public function getManager() {
        return $this->hasOne(Users::className(), ['id' => 'manager_id']);
    }
    
    public function getType() {
        return $this->hasOne(ActionsTypes::className(), ['id' => 'type_id']);
    }
    
    public static function add($type_id, $data = [])
    {
        $model = new self();
        $model->project_id = Projects::getCurrent();
        $model->manager_id = Yii::$app->user->id;
        $model->object_id = $data['id'];
        $model->type_id = $type_id;
        $model->info = Json::encode($data);
        $model->created = time();
        return $model->save();
    }
    
    public function getInfo()
    {
        return Json::decode($this->info);
    }

    public function attributeLabels()
    {
        return Normalize::withCommonLabels([
            'object_id' => 'Id объекта',
            'type_id' => 'Тип событие',
            'info' => 'Информация',
        ]);
    }

    public static function find()
    {
        return new ActionsQuery(get_called_class());
    }
}
