<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\GalleryController;
use yii\helpers\Url;
use app\models\forms\UploadForm;

/**
 * @var $model \app\models\Gallery
 */

$this->title = 'Управление картинками';
$this->params['breadcrumbs'] = [
    ['label' => GalleryController::LIST_NAME, 'url' => ['list']],
    ['label' => $model->name, 'url' => ['edit', 'id' => $model->id]],
    ['label' => $this->title]
];

$form = ActiveForm::begin();
?>
<?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
<? \app\helpers\MHtml::alertMsg(); ?>

<div class="w-1200">
    <? if ($model->id && $model->images_f): ?>
        <div class="box box-success">
            <div class="box-header with-border"><?= $this->title . " гарелеи \"{$model->name}\"" ?></div>
            <div class="box-body">
                <? foreach ($model->images_f AS $ik => $img): ?>
                    <div class="gallery-item">
                        <div class="row">
                            <div class="col-xs-12 col-md-4">
                                <a href="<?= UploadForm::getSrc($model->images_f[$ik], UploadForm::TYPE_GALLERY) ?>"
                                   class="gallery-item__photo" rel="gal" title="<?= Html::encode($model->images_t[$ik]) ?>">
                                    <img src="<?= UploadForm::getSrc($model->images_f[$ik], UploadForm::TYPE_GALLERY) ?>" alt=""/>
                                </a>
                            </div>
                            <div class="col-xs-12 col-md-6">
                                <div class="row">
                                    <div class="col-xs-3">
                                        <div class="row">
                                            <p class="form-control-static text-left">Альбом:</p>
                                        </div>
                                    </div>
                                    <div class="col-xs-9">
                                        <?= $form->field($model, "images_i[{$ik}]", ['template' => '{input}'])
                                            ->dropDownList(\app\models\Gallery::getFilterList(), [
                                                'class' => 'form-control',
                                                'encode' => false,
                                        ]) ?>
                                    </div>
                                </div>
                                <div class="row">
                                    <div class="input-group m-t-15">
                                        <span class="input-group-addon">Имя файла:</span>
                                        <?= Html::activeHiddenInput($model, "images_o[{$ik}]", ['class' => 'form-control']) ?>
                                        <?= Html::activeTextInput($model, "images_f[{$ik}]", ['class' => 'form-control']) ?>
                                    </div>
                                    <div class="input-group m-t-15">
                                        <span class="input-group-addon">Описание фото:</span>
                                        <?= Html::activeTextInput($model, "images_t[{$ik}]", ['class' => 'form-control']) ?>
                                    </div>
                                    <div class="input-group m-t-15">
                                        <span class="input-group-addon">Alt-аттрибут:</span>
                                        <?= Html::activeTextInput($model, "images_a[{$ik}]", ['class' => 'form-control']) ?>
                                    </div>
                                    <div class="m-t-20">
                                        <button type="button" class="btn btn-danger btn-xs" onclick="removeGalleryItem(this)">Удалить</button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12 col-md-2">
                                <div class="text-right">
                                    <div class="input-group gallery-action">
                                        <button class="btn btn-default act-up" title="Поднять вверх" onclick="Gallery.up(this)"><i class="fa fa-chevron-up"></i></button>
                                        <button class="btn btn-default act-down" title="Опустить вниз" onclick="Gallery.down(this)"><i class="fa fa-chevron-down"></i></button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <? endforeach; ?>
    
                <div class="row">
                    <div class="col-xs-12 col-md-offset-4 col-md-8">
                        <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                    </div>
                </div>
            </div>
        </div>
    <? else: ?>
        <div class="alert alert-info">Нет загруженных картинок</div>
    <? endif; ?>
</div>
<?php ActiveForm::end() ?>
<script>
    function removeGalleryItem(obj) {
        if (confirm('Подтверждаете удаление картинки?')) {
            $(obj).closest('.gallery-item').remove();
        }
    }
</script>
<?php
$this->registerJs("
$('.gallery-item__photo').colorbox({
    rel: 'gal'
});
");