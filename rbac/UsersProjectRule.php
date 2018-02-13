<?php
namespace app\rbac;

use app\models\Users;
use Yii;
use yii\rbac\Rule;

/**
 * проверка доступа к проекту
 * Class UsersProjectRule
 * @package app\rbac
 */
class UsersProjectRule extends Rule
{
    public $name = 'userProject';
    
    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            /** @var $identity Users */
            $identity = Yii::$app->user->identity;
            // разрешено админу или менеджеру с доступом к проекту
            return Yii::$app->user->can(Users::ROLE_ADMIN) || in_array($params['id'], $identity->projects_arr);
        }
        
        return false;
    }
}