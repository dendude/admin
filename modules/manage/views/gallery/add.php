<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\GalleryController;
use yii\helpers\Url;
use app\models\forms\UploadForm;

/**
 * @var $model \app\models\Gallery
 */

$this->title = $model->id ? 'Редактирование галереи' : 'Добавление галереи';
$this->params['breadcrumbs'] = [
    ['label' => GalleryController::LIST_NAME, 'url' => ['list']],
    ['label' => $this->title]
];

$form = ActiveForm::begin();

$inputMiddle = ['inputOptions' => ['class' => 'form-control input-middle']];
?>

<?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
<? \app\helpers\MHtml::alertMsg(); ?>

<div class="row">
    <div class="col-xs-12 col-lg-7">
        <div class="box box-primary">
            <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
            <div class="box-body">
                <?= $form->field($model, 'parent_id')->dropDownList(\app\models\Gallery::getFilterList(), [
                    'encode' => false, 'prompt' => '--', 'class' => 'form-control select2'
                ]) ?>
                <div class="separator"></div>
                <?= $form->field($model, 'name') ?>
                <?= \app\helpers\MHtml::aliasField($model, 'alias', 'alias') ?>
                <div class="row row-comment">
                    <div class="col-xs-offset-4 col-xs-8">
                        Пример: вводим "раздел/название", клик "Получить URL" покажет "razdel/nazvanie".<br/>
                        После сохранения ссылка на страницу будет такой: "razdel/nazvanie.html".
                    </div>
                </div>
                <div class="separator"></div>
                <?= $form->field($model, 'bread_name') ?>
                <div class="separator"></div>
                <?= $form->field($model, 'meta_t')->textarea(['maxlength' => true]) ?>
                <?= $form->field($model, 'meta_d')->textarea(['maxlength' => true]) ?>
                <?= $form->field($model, 'meta_k')->textarea(['maxlength' => true]) ?>
                <div class="separator"></div>
                <?= $form->field($model, 'is_gallery')->checkbox(['class' => 'ichecks']) ?>
                <?= $form->field($model, 'status')->checkbox(['label' => 'Опубликовать', 'class' => 'ichecks']) ?>
                <div class="separator"></div>
                <div class="form-group">
                    <div class="col-xs-offset-4 col-xs-8">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
                <div class="separator"></div>
                <?= $form->field($model, 'content', ['template' => '<div class="col-xs-4 text-right">{label}{error}</div>']) ?>
                <?= \app\modules\manage\widgets\FroalaEditorWidget::widget([
                    'model' => $model,
                    'field' => 'content',
                    'imageUploadUrl' => Url::to(['upload-image']),
                    'filesUploadUrl' => Url::to(['upload-files']),
                ]); ?>
                <div class="separator"></div>
                <div class="separator"></div>
                <div class="form-group">
                    <div class="col-xs-offset-4 col-xs-8">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xs-12 col-lg-5">
        <div class="box box-primary">
            <div class="box-header with-border">Фотографии галереи</div>
            <div class="box-body">
                <?= \app\modules\manage\widgets\DropZoneWidget::widget([
                    'model' => $model,
                    'field' => 'images_f[]',
                    'zone_id' => 'images_f_zone',
                    'url' => \yii\helpers\Url::to(['upload']),
                    'max_files' => 1000,
                    'isDeleteBtn' => false,
                ]); ?>
                
                <div class="m-t-50">
                    <?= Html::hiddenInput('move-to-photos', 0, ['id' => 'move-to-photos']); ?>
                    <?= Html::submitButton('Сохранить и перейти к управлению', [
                        'class' => 'btn btn-primary',
                        'onclick' => "$('#move-to-photos').val(1)",
                    ]); ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>
<?php
// для показа кол-ва символов у редактируемой страницы
$this->registerJs("
$('textarea').on('keyup', function(){
    charsCalculate(this);
}).keyup();
");