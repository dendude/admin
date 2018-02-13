<?php
namespace app\models\traits;

use app\helpers\Statuses;
use app\models\Projects;
use app\models\Users;

/**
 * Class GetManagerTrait
 *
 * трейт получения менеджера из модели
 *
 * @package app\models\traits
 */
trait BeforeValidateTrait
{
    public function beforeValidateTrait()
    {
        if ($this->isNewRecord) {
            $this->created = time();
            $this->project_id = Projects::getCurrent();
        } else {
            $this->modified = time();
        }
        
        $this->manager_id = \Yii::$app->user->id;
    }
}