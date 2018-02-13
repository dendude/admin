<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\NewsSectionsController;
use \app\models\NewsSections;

/** @var $model NewsSections */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование разедла новостей' : 'Создание раздела новостей';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => NewsSectionsController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$w100 = ['inputOptions' => ['class' => 'form-control w-100']];
$form = ActiveForm::begin();
?>
<div class="w-1200">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <?= \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body p-t-20 p-b-20">
            <div class="form-group <? if ($model->isAttributeRequired('photo')): ?>required<? endif; ?>">
                <div class="col-xs-12 col-md-4 text-right">
                    <label for="" class="control-label"><?= $model->getAttributeLabel('photo') ?></label>
                </div>
                <div class="col-xs-12 col-md-8">
                    <?= \app\modules\manage\widgets\DropZoneWidget::widget([
                        'model' => $model,
                        'field' => 'photo',
                        'zone_id' => 'photo_zone',
                        'url' => \yii\helpers\Url::to(['upload']),
                    ]); ?>
                </div>
            </div>
            <div class="separator"></div>
            <?= $form->field($model, 'title') ?>
            <?= \app\helpers\MHtml::aliasField($model, 'alias', 'alias') ?>
            <div class="separator"></div>
            <?= $form->field($model, 'meta_t')->textarea(['maxlength' => true]) ?>
            <?= $form->field($model, 'meta_d')->textarea(['maxlength' => true]) ?>
            <?= $form->field($model, 'meta_k')->textarea(['maxlength' => true]) ?>
            <div class="separator"></div>
            <?= $form->field($model, 'ordering', $w100)->input('number', ['step' => 1]) ?>
            <div class="separator"></div>
            <?= $form->field($model, 'status')->checkbox(['class' => 'ichecks', 'label' => 'Опубликовать на сайте']) ?>
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

<?php
// для показа кол-ва символов у редактируемой новости
$this->registerJs("
$('textarea').on('keyup', function(){
    charsCalculate(this);
}).keyup();
");