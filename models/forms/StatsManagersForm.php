<?php
namespace app\models\forms;

use yii\base\Model;

class StatsManagersForm extends Model
{
    public $project_id;
    public $agent_id;
    
    public $date_from;
    public $date_to;
    
    public $is_unique = false;

    public function rules()
    {
        return [
            [['project_id', 'agent_id'], 'integer'],
            [['date_from', 'date_to'], 'date', 'format' => 'dd.MM.yyyy'],
            ['is_unique', 'boolean'],
        ];
    }
    
    public function attributeLabels()
    {
        return [
            'project_id' => 'Проект',
            'agent_id' => 'Менеджер',
            'date_from' => 'Дата с',
            'date_to' => 'Дата по',
            'is_unique' => 'Уникальные',
        ];
    }
}
