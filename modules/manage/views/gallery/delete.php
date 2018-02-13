<?php
use yii\helpers\Html;

/**
 * @var $model \app\models\Gallery
 */

$this->title = 'Удаление галереи';
$this->params['breadcrumbs'] = [
    ['label' => \app\modules\manage\controllers\GalleryController::LIST_NAME, 'url' => ['list']],
    ['label' => $this->title]
];
?>
<div class="w-600">
    <div class="box box-widget widget-user-2">
        <div class="widget-user-header bg-red strong">Подтверждаете удаление галереи?</div>
        <div class="box-body">
            <div class="separator"></div>
            <? if ($model->parent): ?>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('parent_id') ?></label>
                <div class="col-xs-6"><?= $model->parent->name ?></div>
            </div>
            <? endif; ?>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('name') ?></label>
                <div class="col-xs-6"><?= $model->name ?></div>
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