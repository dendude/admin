<?php
namespace app\modules\manage\controllers;

use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;
use app\helpers\Statuses;
use app\models\forms\UploadForm;
use app\models\News;
use app\models\search\NewsSearch;

class NewsController extends Controller
{
    const LIST_NAME = 'Новости';
        
    protected function notFound($message = 'Новость не найдена') {
        Yii::$app->session->setFlash('error', $message);
        return $this->redirect(['list']);
    }
    
    public function actionList()
    {
        $searchModel = new NewsSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }
    
    public function actionAdd() {
        $model = new News();

        if (Yii::$app->request->post('News')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                return $this->redirect(['list']);
            }
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionEdit($id) {
        $model = News::findOne($id);
        if (!$model) $this->notFound();
        
        if (Yii::$app->request->post('News')) {
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
        $model = News::findOne($id);
        if (!$model) $this->notFound();
        
        $config = UploadForm::getConfig();
        $url = $config['protocol'] . '://' . $config['domain'] . Url::to(["/new/{$model->alias}"]);
        
        return $this->redirect($url);
    }
    
    public function actionDelete($id, $confirm = false) {
        $model = News::findOne($id);
        if (!$model) $this->notFound();
        
        if ($confirm) {
            $model->updateAttributes([
                'status' => Statuses::STATUS_REMOVED,
                'is_slider' => Statuses::STATUS_DISABLED,
            ]);
            Yii::$app->session->setFlash('success', 'Новость успешно удалена');
            return $this->redirect(['list']);
        }
        
        return $this->render('delete', [
            'model' => $model
        ]);
    }
    
    public function actionTrash($id) {
        $this->actionDelete($id, true);
    }
    
    public function actionSaveTempContent() {
        return false;
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
    
    public function actionUploadImage() {
        $upload_form = new UploadForm();
        $upload_form->imgFile = UploadedFile::getInstances($upload_form, 'imgFile');
        if ($upload_form->upload(UploadForm::TYPE_NEWS)) {
            echo Json::encode(['link' => $upload_form->getImgPath()]);
        } else {
            echo Json::encode($upload_form->getErrors());
        }
    }
    
    public function actionUploadFiles() {
        $upload_form = new UploadForm();
        $upload_form->docFile = UploadedFile::getInstances($upload_form, 'docFile');
        if ($upload_form->uploadDoc()) {
            echo Json::encode(['link' => $upload_form->getDocPath(true)]);
        } else {
            echo Json::encode($upload_form->getErrors());
        }
    }
}