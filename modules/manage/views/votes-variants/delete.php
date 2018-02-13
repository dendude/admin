<?php
use yii\helpers\Html;
use app\modules\manage\controllers\VotesController;
use app\modules\manage\controllers\VotesVariantsController;

/** @var $model \app\models\VotesVariants */

$this->title = 'Удаление варианта ответа';
$this->params['breadcrumbs'] = [
    ['label' => VotesController::LIST_NAME, 'url' => ['list']],
    ['label' => VotesVariantsController::LIST_NAME, 'url' => ['votes-variants/list']],
    ['label' => $this->title]
];
?>
<div class="w-700">
    <div class="box box-widget widget-user-2">
        <div class="widget-user-header bg-red strong">Подтверждаете удаление?</div>
        <div class="box-body">
            <div class="separator"></div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('vote_id') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->vote->name) ?></div>
            </div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('title') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->title) ?></div>
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