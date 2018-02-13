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
 * @property Users manager
 *
 * @package app\models\traits
 */
trait GetManagerTrait
{
    public function getManager() {
        return $this->hasOne(Users::className(), ['id' => 'manager_id']);
    }
}