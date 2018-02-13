<?php
namespace app\modules\manage\controllers;

use app\models\search\VotesSearch;
use app\models\search\VotesVariantsSearch;
use app\models\Votes;
use app\models\VotesVariants;
use Yii;
use yii\helpers\Html;
use yii\web\Controller;
use app\helpers\Statuses;

class VotesController extends Controller
{
    const LIST_NAME = 'Виджеты голосования';
        
    protected function notFound($message = 'Голосование не найдено')
    {
        Yii::$app->session->setFlash('error', $message);
        return $this->redirect(['list']);
    }
    
    public function actionList()
    {
        $searchModel = new VotesSearch();
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
        $model = new Votes();

        if (Yii::$app->request->post('Votes')) {
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
        $model = Votes::findOne($id);
        if (!$model) return $this->notFound();

        if (Yii::$app->request->post('Votes')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                return $this->redirect(['list']);
            }
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionShow($id)
    {
        $model = new VotesVariantsSearch();
        $params = [
            'manage/votes-variants/list',
            Html::getInputName($model, 'vote_id') => $id
        ];
        
        Yii::$app->response->redirect($params)->send();
        Yii::$app->end();
    }
    
    public function actionDelete($id, $confirm = false)
    {
        $model = Votes::findOne($id);
        if (!$model) $this->notFound();
        
        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            $model->deleteFromPages();
            
            Yii::$app->session->setFlash('success', 'Голосование успешно удалено');
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