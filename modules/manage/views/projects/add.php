<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\ProjectsController;

/**
 * @var $this \yii\web\View
 * @var $model \app\models\Projects
 */

$action = $model->id ? 'Редактирование проекта' : 'Добавление проекта';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => ProjectsController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$w100 = ['inputOptions' => ['class' => 'form-control w-100']];
$w300 = ['inputOptions' => ['class' => 'form-control w-300']];

$form = ActiveForm::begin();
?>
<div class="w-1000">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <? \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body">
            <?= $form->field($model, 'site_name') ?>
            <?= $form->field($model, 'site_url') ?>
            <?= $form->field($model, 'site_icon', $w300) ?>
            <?= $form->field($model, 'mark_text', $w300) ?>
    
            <div class="separator"></div>
            
            <?= $form->field($model, 'ordering', $w100)->input('number') ?>
    
            <div class="separator"></div>
    
            <?= $form->field($model, 'status')->checkbox(['class' => 'ichecks', 'label' => 'Опубликовать для менеджеров']) ?>
            
            <div class="separator"></div>

            <div class="form-group">
                <div class="col-xs-offset-4 col-xs-2">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>