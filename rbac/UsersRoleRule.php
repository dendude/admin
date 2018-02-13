<?php
namespace app\rbac;

use app\models\Users;
use Yii;
use yii\rbac\Rule;

/**
 * роли пользователей
 * Class UsersRoleRule
 * @package app\rbac
 */
class UsersRoleRule extends Rule
{
    public $name = 'userRole';
    
    public function execute($user, $item, $params)
    {
        if (!Yii::$app->user->isGuest) {
            /** @var $identity Users */
            $identity = Yii::$app->user->identity;
            
            $check = [];
            switch ($item->name) {
                case Users::ROLE_USER :
                    $check[] = Users::ROLE_USER;
                    $check[] = Users::ROLE_PARTNER;
                    $check[] = Users::ROLE_MANAGER;
                    $check[] = Users::ROLE_ADMIN;
                    break;
                
                case Users::ROLE_PARTNER :
                    $check[] = Users::ROLE_PARTNER;
                    $check[] = Users::ROLE_MANAGER;
                    $check[] = Users::ROLE_ADMIN;
                    break;
    
                case Users::ROLE_MANAGER :
                    $check[] = Users::ROLE_MANAGER;
                    $check[] = Users::ROLE_ADMIN;
                    break;
    
                case Users::ROLE_ADMIN :
                    $check[] = Users::ROLE_ADMIN;
                    break;
            }
            
            return count($check) && in_array($identity->role, $check);
        }
        
        return false;
    }
}