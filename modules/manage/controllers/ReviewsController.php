<?php
namespace app\modules\manage\controllers;

use app\models\forms\UploadForm;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use app\helpers\Statuses;
use app\models\Reviews;
use app\models\search\ReviewsSearch;
use yii\web\Response;
use yii\web\UploadedFile;

class ReviewsController extends Controller
{
    const LIST_NAME = 'Отзывы';
        
    protected function notFound($message = 'Отзыв не найдена')
    {
        Yii::$app->session->setFlash('error', $message);
        return $this->redirect(['list']);
    }
    
    public function actionShow($id)
    {
        $model = Reviews::findOne($id);
        if (!$model) return $this->notFound();
    
        $config = UploadForm::getConfig();
        
        return $this->redirect("//{$config['domain']}" . Url::to(["/review/{$model->id}"]));
    }
    
    public function actionList()
    {
        $searchModel = new ReviewsSearch();
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
        $model = new Reviews();

        if (Yii::$app->request->post('Reviews')) {
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
        $model = Reviews::findOne($id);
        if (!$model) return $this->notFound();

        if (Yii::$app->request->post('Reviews')) {
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
        $model = Reviews::findOne($id);
        if (!$model) $this->notFound();
        
        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            Yii::$app->session->setFlash('success', 'Отзыв успешно удален');
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
        if ($upload_form->upload(UploadForm::TYPE_REVIEWS)) {
            return ['file_name' => $upload_form->getImgPath()];
        } else {
            return $upload_form->getErrors();
        }
    }
}