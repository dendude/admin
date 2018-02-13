<?php
namespace app\modules\manage\controllers;

use app\models\forms\StatsManagersForm;
use app\models\Users;
use Yii;
use yii\web\Controller;
use app\models\search\ActionsSearch;

class StatsController extends Controller
{
    const LIST_NAME = 'Статистика менеджеров';
    
    public function beforeAction($action)
    {
        if (!Yii::$app->user->can(Users::ROLE_ADMIN)) {
            die;
        }
        
        return parent::beforeAction($action);
    }
    
    
    public function actionIndex()
    {
        $model = new StatsManagersForm();
        
        if (Yii::$app->request->get('StatsManagersForm')) {
            $model->load(Yii::$app->request->get());
        }
        
        return $this->render('index', [
            'model' => $model
        ]);
    }
}