<?php
namespace app\modules\manage\controllers;

use Yii;
use yii\web\Controller;
use app\models\search\ActionsSearch;

class ActionsController extends Controller
{
    const LIST_NAME = 'Действия менеджеров';
    
    public function actionList()
    {
        $searchModel = new ActionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
}