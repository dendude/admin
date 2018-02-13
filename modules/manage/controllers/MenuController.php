<?php
namespace app\modules\manage\controllers;

use app\helpers\Statuses;
use app\models\forms\UploadFileForm;
use app\models\forms\UploadForm;
use app\models\Menu;
use app\models\Pages;
use app\models\search\PagesSearch;
use Yii;
use yii\helpers\Json;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\Response;
use yii\web\UploadedFile;

class MenuController extends Controller
{
    const LIST_NAME = 'Меню сайта';
        
    protected function notFound($message = 'Пункт меню не найден')
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
        $model = new Menu();

        if (Yii::$app->request->post('Menu')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Пункт меню успешно добавлен');
                return $this->redirect(['list']);
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
        $model = Menu::findOne($id);
        if (!$model) $this->notFound();

        if (Yii::$app->request->post('Menu')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Пункт меню успешно изменен');
                return $this->redirect(['list']);
            }
        }
        
        return $this->render('add', [
            'model' => $model
        ]);
    }
    
    public function actionDelete($id, $confirm = false)
    {
        $model = Menu::findOne($id);
        if (!$model) $this->notFound();
        
        if ($confirm) {
            $model->updateAttributes(['status' => Statuses::STATUS_REMOVED]);
            Yii::$app->session->setFlash('success', 'Пункт меню успешно удален');
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
    
    public function actionShow($id) {
        Menu::updateAll(['status' => Statuses::STATUS_ACTIVE], ['id' => $id]);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('list');
        } else {
            return $this->redirect(['list']);
        }
    }
    
    public function actionHide($id) {
        Menu::updateAll(['status' => Statuses::STATUS_DISABLED], ['id' => $id]);
        
        if (Yii::$app->request->isAjax) {
            return $this->renderPartial('list');
        } else {
            return $this->redirect(['list']);
        }
    }
    
    public function actionUp($id) {
        $current = Menu::findOne($id);
        if ($current) {
            $prev = Menu::find()->byParent($current->parent_id)->andWhere(
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
        $current = Menu::findOne($id);
        if ($current) {
            $prev = Menu::find()->byParent($current->parent_id)->andWhere(
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
}