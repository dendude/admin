<?php
namespace app\modules\manage\controllers;

use app\helpers\Statuses;
use app\models\search\UsersSearch;
use app\models\Users;
use Yii;
use yii\web\Controller;

class UsersController extends Controller
{
    const LIST_NAME = 'Пользователи';

    protected function notFound($message = 'Пользователь не найден') {
        Yii::$app->session->setFlash('error', $message);
        $this->redirect(['list'])->send();
        Yii::$app->end();
    }

    public function actionList()
    {
        $searchModel = new UsersSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->get());

        return $this->render('list', [
            'dataProvider' => $dataProvider,
            'searchModel' => $searchModel,
        ]);
    }

    public function actionAdd() {
        $model = new Users();

        if (Yii::$app->request->post('Users')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Пользователь успешно добавлен');
                return $this->redirect(['list']);
            }
        }

        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionEdit($id) {

        $model = Users::findOne($id);
        if (!$model) $this->notFound();

        if (Yii::$app->request->post('Users')) {
            $model->load(Yii::$app->request->post());
            if ($model->save()) {
                Yii::$app->session->setFlash('success', 'Пользователь успешно сохранен');
                if (Yii::$app->request->post('ref-page')) {
                    return $this->redirect(Yii::$app->request->post('ref-page'));
                } else {
                    return $this->redirect(['list']);
                }
            }
        }

        return $this->render('add', [
            'model' => $model
        ]);
    }

    public function actionDelete($id, $confirm = false) {
        $model = Users::findOne($id);
        if (!$model) $this->notFound();

        if ($confirm) {
            $model->updateAttributes([
                'email' => '', // освобождаем email
                'status' => Statuses::STATUS_REMOVED
            ]);
            Yii::$app->session->setFlash('success', 'Пользователь успешно удален');
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
