<?php
namespace app\commands;

use app\models\Actions;
use app\models\News;
use app\models\Pages;
use yii\console\Controller;
use yii\helpers\Json;

/**
 * импорт сделанной работы менеджеров
 *
 * @package app\commands
 */
class ActionsController extends Controller
{
    public function actionRun()
    {
        /** @var $models Actions[] */
        $models = Actions::find()->where(['object_id' => null])->all();
        foreach ($models AS $m) {
            $info = $m->getInfo();
            $m->updateAttributes(['object_id' => $info['id']]);
        }
    }
}