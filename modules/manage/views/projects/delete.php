<?php
use yii\helpers\Html;
use app\modules\manage\controllers\ProjectsController;

/**
 * @var $model \app\models\Projects
 */

$this->title = 'Удаление проекта';
$this->params['breadcrumbs'] = [
    ['label' => ProjectsController::LIST_NAME, 'url' => ['list']],
    ['label' => $this->title]
];
?>
<div class="w-700">
    <div class="box box-widget widget-user-2">
        <div class="widget-user-header bg-red strong">Подтверждаете удаление?</div>
        <div class="box-body p-t-20 p-b-20">
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('site_name') ?></label>
                <div class="col-xs-6"><?= Html::encode($model->site_name) ?></div>
            </div>
            <div class="row">
                <label class="col-xs-6 text-right"><?= $model->getAttributeLabel('site_url') ?></label>
                <div class="col-xs-6"><?= Html::a($model->site_url, $model->site_url, ['target' => '_blank']) ?></div>
            </div>
            
            <div class="row m-t-15">
                <div class="col-xs-offset-2 col-xs-4">
                    <?= Html::a('Удалить', ['trash', 'id' => $model->id], ['class' => 'btn btn-danger btn-flat btn-block']) ?>
                </div>
                <div class="col-xs-4">
                    <?= Html::a('Отмена', ['list'], ['class' => 'btn btn-default btn-flat btn-block']) ?>
                </div>
            </div>
        </div>
    </div>
</div>