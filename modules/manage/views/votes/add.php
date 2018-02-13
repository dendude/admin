<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\VotesController;

/** @var $model \app\models\Votes */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование голосования' : 'Создание голосования';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => VotesController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$form = ActiveForm::begin();
?>
<div class="w-900">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <?= \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body p-t-20 p-b-20">
            <?= $form->field($model, 'name') ?>
            <?= $form->field($model, 'title') ?>
            <div class="separator"></div>
            <?= $form->field($model, 'about')->textarea() ?>
            <div class="separator"></div>
            <?= $form->field($model, 'status')->checkbox(['class' => 'ichecks', 'label' => 'Опубликовать']) ?>
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