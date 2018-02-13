<?php
namespace app\commands;

use app\rbac\UsersProjectRule;
use Yii;
use yii\console\Controller;
use app\models\Users;
use app\rbac\UsersRoleRule;

/**
 * инициализация прав
 * Class RbacController
 * @package app\commands
 */
class RbacController extends Controller
{
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();
            
        $rule_role = new UsersRoleRule();
        $auth->add($rule_role);
    
        $rule_project = new UsersProjectRule();
        $auth->add($rule_project);
            
        // создаем роли
    
        $role_project = $auth->createRole('project');
        $role_project->description = 'Проект';
        $role_project->ruleName = $rule_project->name;
        $auth->add($role_project);
        
        $role_user = $auth->createRole(Users::ROLE_USER);
        $role_user->description = 'Пользователь';
        $role_user->ruleName = $rule_role->name;
        $auth->add($role_user);
        
        $role_partner = $auth->createRole(Users::ROLE_PARTNER);
        $role_partner->description = 'Партнер';
        $role_partner->ruleName = $rule_role->name;
        $auth->add($role_partner);
        $auth->addChild($role_partner, $role_user);
        
        $role_manager = $auth->createRole(Users::ROLE_MANAGER);
        $role_manager->description = 'Менеджер';
        $role_manager->ruleName = $rule_role->name;
        $auth->add($role_manager);
        $auth->addChild($role_manager, $role_partner);
        $auth->addChild($role_manager, $role_project);
        
        $role_admin = $auth->createRole(Users::ROLE_ADMIN);
        $role_admin->description = 'Администратор';
        $role_admin->ruleName = $rule_role->name;
        $auth->add($role_admin);
        $auth->addChild($role_admin, $role_manager);
        $auth->addChild($role_admin, $role_project);
    }
}