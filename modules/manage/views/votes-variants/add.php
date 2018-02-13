<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\VotesController;
use app\modules\manage\controllers\VotesVariantsController;

/** @var $model \app\models\Votes */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование варианта ответа' : 'Добавление варианта ответа';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => VotesController::LIST_NAME, 'url' => ['votes/list']],
    ['label' => VotesVariantsController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$w100 = ['inputOptions' => ['class' => 'form-control w-100']];

$form = ActiveForm::begin();
?>
<div class="w-900">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <?= \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body p-t-20 p-b-20">
            <?= $form->field($model, 'vote_id')->dropDownList(\app\models\Votes::getFilterList(), ['prompt' => '']) ?>
            <div class="separator"></div>
            <?= $form->field($model, 'title') ?>
            <?= $form->field($model, 'ordering', $w100)->textInput(['type' => 'number']) ?>
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