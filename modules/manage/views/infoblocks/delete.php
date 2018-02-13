<?php
use yii\helpers\Html;
use app\modules\manage\controllers\InfoblocksController;

/** @var $model \app\models\Infoblocks */

$this->title = 'Удаление инфоблока';
$this->params['breadcrumbs'] = [
    ['label' => InfoblocksController::LIST_NAME, 'url' => ['list']],
    ['label' => $this->title]
];
?>
<div class="w-700">
    <div class="box box-widget widget-user-2">
        <div class="widget-user-header bg-red strong">Подтверждаете удаление?</div>
        <div class="box-body">
            <div class="separator"></div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('title') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->title) ?></div>
            </div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('pages') ?></label>
                <div class="col-xs-6"><?= implode('<br/>', $model->getPagesInfo()) ?></div>
            </div>
            <div class="separator"></div>
            <div class="row">
                <div class="col-xs-offset-2 col-xs-4">
                    <?= Html::a('Удалить', ['trash', 'id' => $model->id], ['class' => 'btn btn-danger btn-flat btn-block']) ?>
                </div>
                <div class="col-xs-4">
                    <?= Html::a('Отмена', ['list'], ['class' => 'btn btn-default btn-flat btn-block']) ?>
                </div>
            </div>
            <div class="separator"></div>
        </div>
    </div>
</div>