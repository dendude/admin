<?php
use yii\helpers\Html;
use app\helpers\LanguageParams;

/**
 * @var $model \app\models\Menu
 */

$this->title = 'Удаление пункта меню';
$this->params['breadcrumbs'] = [
    ['label' => \app\modules\manage\controllers\MenuController::LIST_NAME, 'url' => ['list']],
    ['label' => $this->title]
];
?>
<div class="w-600">
    <div class="box box-widget widget-user-2">
        <div class="widget-user-header bg-red strong">Подтверждаете удаление пункта меню?</div>
        <div class="box-body">
            <div class="separator"></div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('parent_id') ?></label>
                <div class="col-xs-6"><?= $model->parent->name ?></div>
            </div>
            <div class="separator"></div>
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