<?php

namespace app\modules\manage\controllers;

use Yii;
use app\models\Projects;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Default controller for the `admin` module
 */
class MainController extends Controller
{
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }
    
    public function actionIndex() {
        $projects = Projects::find()->active()->ordering()->all();
        
        return $this->render('index', [
            'projects' => $projects
        ]);
    }
    
    public function actionProject($id) {
        /** @var $project Projects */
        $project = Projects::find()->active()->where(['id' => $id])->one();
        
        if (!$project) throw new NotFoundHttpException('Проект не найден');
        if (!Yii::$app->user->can('project', ['id' => $id])) {
            throw new ForbiddenHttpException('Доступ к проекту запрещен');
        }
    
        $project->setCurrent();
                
        return $this->redirect(['index']);
    }
}
