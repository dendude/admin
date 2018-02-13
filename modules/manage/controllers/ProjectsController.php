<?php
namespace app\modules\manage\controllers;

use app\helpers\Statuses;
use app\models\Projects;
use app\models\search\ProjectsSearch;
use yii\web\Controller;
use Yii;

class ProjectsController extends Controller
{
    const LIST_NAME = 'Проекты';
    
    protected function notFound($message = 'Проект не найден') {
        Yii::$app->session->setFlash('error', $message);
        $this->redirect(['list'])->send();
        Yii::$app->end();
    }
    
    public function actionList() {
        $searchModel = new ProjectsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionAdd() {
        $model = new Projects();
        
        if (Yii::$app->request->post('Projects')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Проект успешно добавлен');
                return $this->redirect(['list']);
            }
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionEdit($id) {
        $model = Projects::findOne($id);
        if (!$model) $this->notFound();
        
        if (Yii::$app->request->post('Projects')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Проект успешно сохранен');
                return $this->redirect(['list']);
            }
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionDelete($id, $confirm = false) {
        $model = Projects::findOne($id);
        if (!$model) $this->notFound();
        
        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            Yii::$app->session->setFlash('success', 'Проект успешно удален');
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
