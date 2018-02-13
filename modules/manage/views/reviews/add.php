<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\ReviewsController;
use app\models\Reviews;

/** @var $model \app\models\Reviews */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование отзыва' : 'Создание отзыва';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => ReviewsController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$w100 = ['inputOptions' => ['class' => 'form-control w-100']];
$w300 = ['inputOptions' => ['class' => 'form-control w-300']];

$settings = \app\models\forms\UploadForm::getConfig();

$form = ActiveForm::begin();
?>
<div class="w-900">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <?= \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body p-t-20 p-b-20">
            <div class="form-group">
                <div class="col-xs-12 col-md-4 text-right">
                    <label for="" class="control-label"><?= $model->getAttributeLabel('img_base') ?></label>
                </div>
                <div class="col-xs-12 col-md-8">
                    <div class="images-icons">
                    <? foreach (Reviews::getImagesIcons() AS $icon_key => $icon_name): ?>
                        <label for="icon_<?= $icon_key ?>" class="text-center m-r-10">
                            <img src="<?= "/{$settings['symlink']}/{$settings['view_dir']}/{$icon_name}" ?>" width="64"><br>
                            <?= Html::radio(Html::getInputName($model, 'img_base'), ($icon_name == $model->img_base), [
                                'id' => "icon_{$icon_key}",
                                'value' => $icon_name,
                                'label' => null,
                            ]) ?>
                        </label>
                    <? endforeach; ?>
                    </div>
                </div>
            </div>
            
            <div class="separator"></div>
            
            <div class="form-group">
                <div class="col-xs-12 col-md-4 text-right">
                    <label for="" class="control-label"><?= $model->getAttributeLabel('img_logo') ?></label>
                </div>
                <div class="col-xs-12 col-md-8">
                    <?= \app\modules\manage\widgets\DropZoneWidget::widget([
                        'model' => $model,
                        'field' => 'img_logo',
                        'zone_id' => 'img_logo_zone',
                        'url' => \yii\helpers\Url::to(['upload']),
                    ]); ?>
                </div>
            </div>
            <div class="row row-comment">
                <div class="col-xs-offset-4 col-xs-8">
                    <?= $model->getAttributeLabel('img_logo') ?>
                    имеет преимущество над полем
                    <?= $model->getAttributeLabel('img_base') ?>
                </div>
            </div>
            
            <div class="separator"></div>
            
            <div class="form-group">
                <div class="col-xs-12 col-md-4 text-right">
                    <label for="" class="control-label"><?= $model->getAttributeLabel('img_name') ?></label>
                </div>
                <div class="col-xs-12 col-md-8">
                    <?= \app\modules\manage\widgets\DropZoneWidget::widget([
                        'model' => $model,
                        'field' => 'img_name',
                        'zone_id' => 'img_name_zone',
                        'url' => \yii\helpers\Url::to(['upload']),
                    ]); ?>
                </div>
            </div>
            
            <div class="separator"></div>
            <?= $form->field($model, 'user_name', $w300) ?>
            <?= $form->field($model, 'user_email', $w300) ?>
            <?= $form->field($model, 'user_review')->textarea(['rows' => 20]) ?>
            <div class="separator"></div>
            <?= $form->field($model, 'bread_name') ?>
            <?= $form->field($model, 'manager_answer')->textarea() ?>
            <?= $form->field($model, 'send_answer')->checkbox(['class' => 'ichecks', 'label' => 'Отправить ответ автору отзыва']) ?>
            <div class="separator"></div>
            <?= $form->field($model, 'meta_t')->textarea(['class' => 'form-control meta-fields']) ?>
            <?= $form->field($model, 'meta_d')->textarea(['class' => 'form-control meta-fields']) ?>
            <?= $form->field($model, 'meta_k')->textarea(['class' => 'form-control meta-fields']) ?>
            <div class="separator"></div>
            <?= $form->field($model, 'ordering', $w100)->input('number') ?>
            <div class="separator"></div>
            <?= $form->field($model, 'status')->checkbox(['class' => 'ichecks', 'label' => 'Опубликовать отзыв на сайте']) ?>
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
// для показа кол-ва символов у редактируемой страницы
$this->registerJs("
$('.meta-fields').on('keyup', function(){
    charsCalculate(this);
}).keyup();
");
