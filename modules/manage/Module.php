<?php

namespace app\modules\manage;

use app\models\Users;
use Yii;
use yii\filters\AccessControl;

/**
 * admin module definition class
 */
class Module extends \yii\base\Module
{
    /**
     * @inheritdoc
     */
    public $controllerNamespace = 'app\modules\manage\controllers';
    public $layout = 'manage';
    
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => [Users::ROLE_MANAGER],
                    ],
                ],
            ],
        ];
    }
    
    /**
     * @inheritdoc
     */
    public function init()
    {
        parent::init();
    
        Yii::$app->errorHandler->errorAction = 'manage/main/error';
    }
}
