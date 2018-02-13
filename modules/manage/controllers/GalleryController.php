<?php
namespace app\modules\manage\controllers;

use app\helpers\Statuses;
use app\models\forms\UploadForm;
use app\models\Gallery;
use Yii;
use yii\helpers\Json;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class GalleryController extends Controller
{
    const LIST_NAME = 'Фотогалереи';
    const TEMP_NAME = 'gallery-tmp';
        
    protected function notFound($message = 'Галерея не найдена')
    {
        Yii::$app->session->setFlash('error', $message);
        return $this->redirect(['list']);
    }
    
    public function actionList()
    {
        return $this->render('list');
    }
    
    public function actionAdd($id = 0)
    {
        $model = new Gallery();

        if (Yii::$app->request->post('Gallery')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                if (Yii::$app->request->post('move-to-photos')) {
                    return $this->redirect(['photos', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('success', 'Галерея успешно добавлена');
                    return $this->redirect(['list']);
                }
            }
        } else {
            $model->parent_id = $id;
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionEdit($id)
    {
        $model = Gallery::findOne($id);
        if (!$model) $this->notFound();

        if (Yii::$app->request->post('Gallery')) {
            $old_o = $model->images_o;
            $old_t = $model->images_t;
            $old_a = $model->images_a;
    
            $model->setScenario(Gallery::SCENARIO_EDIT);
            $model->load(Yii::$app->request->post());
            
            $model->images_o = $old_o;
            $model->images_t = $old_t;
            $model->images_a = $old_a;
            
            if ($model->save()) {
                if (Yii::$app->request->post('move-to-photos')) {
                    return $this->redirect(['photos', 'id' => $model->id]);
                } else {
                    Yii::$app->session->setFlash('success', 'Галерея успешно изменена');
                    return $this->redirect(['list']);
                }
            }
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionPhotos($id)
    {
        $model = Gallery::findOne($id);
        if (!$model) $this->notFound();
    
        if (Yii::$app->request->post('Gallery')) {
            $model->load(Yii::$app->request->post());

            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Галерея успешно изменена');
                return $this->redirect(['list']);
            }
        }
        
        return $this->render('photos', [
            'model' => $model
        ]);
    }
    
    public function actionDelete($id, $confirm = false)
    {
        $model = Gallery::findOne($id);
        if (!$model) $this->notFound();
        
        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            Yii::$app->session->setFlash('success', 'Галерея успешно удалена');
            return $this->redirect(['list']);
        }
        
        return $this->render('delete', [
            'model' => $model
        ]);
    }
    
    public function actionTrash($id)
    {
        $this->actionDelete($id, true);
    }
    
    public function actionUpload() {
        $upload_form = new UploadForm();
        $upload_form->imgFile = UploadedFile::getInstances($upload_form, 'imgFile');
        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($upload_form->upload(UploadForm::TYPE_GALLERY)) {
            return ['file_name' => $upload_form->getImgPath()];
        } else {
            return $upload_form->getErrors();
        }
    }
    
    public function actionShow($id) {
        Gallery::updateAll(['status' => Statuses::STATUS_ACTIVE], ['id' => $id]);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('list');
        } else {
            return $this->redirect(['list']);
        }
    }
    
    public function actionHide($id) {
        Gallery::updateAll(['status' => Statuses::STATUS_DISABLED], ['id' => $id]);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('list');
        } else {
            return $this->redirect(['list']);
        }
    }
    
    public function actionUp($id) {
        $current = Gallery::findOne($id);
        if ($current) {
            $prev = Gallery::find()->byParent($current->parent_id)->andWhere(
                'ordering < :ord', [':ord' => $current->ordering]
            )->orderBy('ordering DESC')->one();
            if ($prev) {
                $prev_ordering = $prev->ordering;
                $prev->updateAttributes(['ordering' => $current->ordering]);
                $current->updateAttributes(['ordering' => $prev_ordering]);
            }
        }
        
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('list');
        } else {
            return $this->redirect(['list']);
        }
    }
    
    public function actionDown($id) {
        $current = Gallery::findOne($id);
        if ($current) {
            $prev = Gallery::find()->byParent($current->parent_id)->andWhere(
                'ordering > :ord', [':ord' => $current->ordering]
            )->orderBy('ordering ASC')->one();
            if ($prev) {
                $prev_ordering = $prev->ordering;
                $prev->updateAttributes(['ordering' => $current->ordering]);
                $current->updateAttributes(['ordering' => $prev_ordering]);
            }
        }
        
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('list');
        } else {
            return $this->redirect(['list']);
        }
    }
    
    public function actionSaveTempContent() {
        $id = Yii::$app->request->post('id', '');
        $content = Yii::$app->request->post('content', '');
        
        Yii::$app->session->set(self::TEMP_NAME . $id, $content);
    }
    
    public function actionUploadImage() {
        $upload_form = new UploadForm();
        $upload_form->imgFile = UploadedFile::getInstances($upload_form, 'imgFile');
        if ($upload_form->upload(UploadForm::TYPE_GALLERY)) {
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