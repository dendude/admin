<?php
namespace app\modules\manage\controllers;

use app\helpers\Statuses;
use app\models\forms\UploadFileForm;
use app\models\forms\UploadForm;
use app\models\Pages;
use app\models\search\PagesSearch;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class PagesController extends Controller
{
    const LIST_NAME = 'Страницы сайта';
    const TEMP_NAME = 'temp-page';
        
    protected function notFound($message = 'Страница не найдена') {
        Yii::$app->session->setFlash('error', $message);
        $this->redirect(['list'])->send();
        Yii::$app->end();
    }
    
    public function actionList()
    {
        $searchModel = new PagesSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());
        
        return $this->render(
            'list', [
                'dataProvider' => $dataProvider,
                'searchModel' => $searchModel,
            ]
        );
    }
    
    public function actionAdd() {
        $model = new Pages();

        $session = Yii::$app->session;

        if (Yii::$app->request->post('Pages')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                $session->offsetUnset(self::TEMP_NAME);
                return $this->redirect(['list']);
            }
        } elseif ($session->offsetExists(self::TEMP_NAME)) {
            // из автосохранения
            $model->content = $session->get(self::TEMP_NAME);
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionEdit($id) {
        $model = Pages::findOne($id);
        if (!$model) $this->notFound();

        $session = Yii::$app->session;

        if (Yii::$app->request->post('Pages')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                $session->offsetUnset(self::TEMP_NAME . $id);

                if (Yii::$app->request->post('ref-page')) {
                    return $this->redirect(Yii::$app->request->post('ref-page'));
                } else {
                    return $this->redirect(['list']);
                }
            }
        } elseif ($session->offsetExists(self::TEMP_NAME . $id)) {
            // из автосохранения
            $model->content = $session->get(self::TEMP_NAME . $id);
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionShow($id)
    {
        $model = Pages::findOne($id);
        if (!$model) $this->notFound();
        
        $config = UploadForm::getConfig();
        
        $url = $config['protocol'] . '://' . $config['domain'];
        if ($model->alias != 'index') $url .= Url::to(["/{$model->alias}"]);
        
        return $this->redirect($url);
    }
    
    public function actionDelete($id, $confirm = false) {
        $model = Pages::findOne($id);
        if (!$model) $this->notFound();
        
        if ($confirm) {
            $model->updateAttributes([
                'status' => Statuses::STATUS_REMOVED,
                'is_sitemap' => Statuses::STATUS_DISABLED,
            ]);
            Yii::$app->session->setFlash('success', 'Страница успешно удалена');
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
        $id = Yii::$app->request->post('id', '');
        $content = Yii::$app->request->post('content', '');
        
        Yii::$app->session->set(self::TEMP_NAME . $id, $content);
    }
    
    public function actionUploadImage() {
        $upload_form = new UploadForm();
        $upload_form->imgFile = UploadedFile::getInstances($upload_form, 'imgFile');
        if ($upload_form->upload(UploadForm::TYPE_PAGES)) {
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
    
//    public function actionFilesManager() {
//        $result = [];
//
//        $common_path = 'img/pages-editor';
//        $common_files = scandir(Yii::getAlias("@app/web/{$common_path}"));
//
//        if (count($common_files)) {
//            foreach ($common_files AS $file_name) {
//                if ($file_name == '.' || $file_name == '..') continue;
//
//                $result[] = [
//                    'url' => "/{$common_path}/$file_name",
//                    'thumb' => "/{$common_path}/$file_name",
//                    'tag' => 'Рейтинг',
//                ];
//            }
//        }
//
//        $pages_files = scandir(Yii::getAlias("@app/web/photos"));
//
//        if (count($pages_files)) {
//            foreach ($pages_files AS $file_name) {
//                // пропускаем корневый и фото не для страниц
//                if ($file_name == '.' || $file_name == '..' || strpos($file_name, UploadForm::TYPE_PAGES . '_') !== 0) continue;
//                // пропускаем миниатюры
//                if (count(explode('_', $file_name)) > 2) continue;
//
//                $result[] = [
//                    'url' => UploadForm::getSrc($file_name),
//                    'thumb' => UploadForm::getSrc($file_name, '_sm'),
//                    'tag' => 'Страницы',
//                ];
//            }
//        }
//
//        Yii::$app->response->format = Response::FORMAT_JSON;
//        return $result;
//    }
    
    public function actionFilesManagerDelete() {
        $src = Yii::$app->request->post('src');
        if ($src) UploadForm::remove($src);
    }
}