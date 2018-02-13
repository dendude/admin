<?php

namespace app\controllers;

use app\models\Projects;
use app\models\Users;
use Yii;
use yii\web\Controller;
use app\models\forms\LoginForm;

class SiteController extends Controller
{
    public function actions() {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex() {
        if (Yii::$app->user->can(Users::ROLE_MANAGER)) {
            $redirect = ['/manage/main/index'];
        } else {
            $redirect = ['login'];
        }
        
        return $this->redirect($redirect);
    }

    public function actionLogin() {
        if (!Yii::$app->user->isGuest) {
            return $this->actionIndex();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->actionIndex();
        }
        
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionLogout() {
        if (Yii::$app->user->logout()) {
            $cookies = Yii::$app->response->cookies;
            $cookies->offsetUnset(Projects::SESSION_KEY);
        }
        
        return $this->goHome();
    }
}
