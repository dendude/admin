<?php
namespace app\modules\manage\controllers;

use app\models\search\VotesVariantsSearch;
use app\models\VotesVariants;
use Yii;
use yii\web\Controller;
use app\helpers\Statuses;

class VotesVariantsController extends Controller
{
    const LIST_NAME = 'Варианты ответов голосования';
        
    protected function notFound($message = 'Вариант ответа не найден')
    {
        Yii::$app->session->setFlash('error', $message);
        return $this->redirect(['list']);
    }
    
    public function actionList()
    {
        $searchModel = new VotesVariantsSearch();
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
        $model = new VotesVariants();

        if (Yii::$app->request->post('VotesVariants')) {
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
        $model = VotesVariants::findOne($id);
        if (!$model) return $this->notFound();

        if (Yii::$app->request->post('VotesVariants')) {
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
        $model = VotesVariants::findOne($id);
        if (!$model) $this->notFound();
        
        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            Yii::$app->session->setFlash('success', 'Вариант успешно удален');
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