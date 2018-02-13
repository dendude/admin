<?php
namespace app\modules\manage\controllers;

use Yii;
use yii\web\Controller;
use app\helpers\Statuses;
use app\models\Infoblocks;
use app\models\search\InfoblocksSearch;

class InfoblocksController extends Controller
{
    const LIST_NAME = 'Инфоблоки страниц сайта';
        
    protected function notFound($message = 'Инфоблок не найдена')
    {
        Yii::$app->session->setFlash('error', $message);
        return $this->redirect(['list']);
    }
    
    public function actionList()
    {
        $searchModel = new InfoblocksSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render(
            'list', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]
        );
    }
    
    public function actionAdd()
    {
        $model = new Infoblocks();

        if (Yii::$app->request->post('Infoblocks')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                return $this->redirect(['list']);
            }
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionEdit($id)
    {
        $model = Infoblocks::findOne($id);
        if (!$model) return $this->notFound();

        if (Yii::$app->request->post('Infoblocks')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                return $this->redirect(['list']);
            }
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionDelete($id, $confirm = false)
    {
        $model = Infoblocks::findOne($id);
        if (!$model) $this->notFound();
        
        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            $model->deleteFromPages();
            
            Yii::$app->session->setFlash('success', 'Инфоблок успешно удален');
            return $this->redirect(['list']);
        }
        
        return $this->render('delete', [
            'model' => $model
        ]);
    }
    
    public function actionTrash($id) {
        $this->actionDelete($id, true);
    }
}