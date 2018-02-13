<?php
namespace app\modules\manage\controllers;

use Yii;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use app\helpers\Statuses;
use app\models\forms\UploadForm;
use app\models\NewsSections;
use app\models\search\NewsSectionsSearch;

class NewsSectionsController extends Controller
{
    const LIST_NAME = 'Разделы новостей';
        
    protected function notFound($message = 'Раздел новостей не найден') {
        Yii::$app->session->setFlash('error', $message);
        return $this->redirect(['list']);
    }
    
    public function actionList()
    {
        $searchModel = new NewsSectionsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionAdd()
    {
        $model = new NewsSections();

        if (Yii::$app->request->post('NewsSections')) {
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
        $model = NewsSections::findOne($id);
        if (!$model) $this->notFound();
        
        if (Yii::$app->request->post('NewsSections')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) return $this->redirect(['list']);
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionDelete($id, $confirm = false) {
        $model = NewsSections::findOne($id);
        if (!$model) $this->notFound();
        
        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            Yii::$app->session->setFlash('success', 'Раздел новостей успешно удален');
            return $this->redirect(['list']);
        }
        
        return $this->render('delete', [
            'model' => $model
        ]);
    }
    
    public function actionTrash($id) {
        $this->actionDelete($id, true);
    }
    
    public function actionUpload() {
        $upload_form = new UploadForm();
        $upload_form->imgFile = UploadedFile::getInstances($upload_form, 'imgFile');
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($upload_form->upload(UploadForm::TYPE_NEWS)) {
            return ['file_name' => $upload_form->getImgPath()];
        } else {
            return $upload_form->getErrors();
        }
    }
}