<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\InfoblocksController;

/** @var $model \app\models\Infoblocks */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование инфоблока' : 'Создание инфоблока';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => InfoblocksController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$form = ActiveForm::begin();
?>
<div class="w-1200">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <?= \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body p-t-20 p-b-20">
            <?= $form->field($model, 'title') ?>
            
            <div class="separator"></div>
    
            <div class="form-group <? if ($model->isAttributeRequired('pages')): ?>required<? endif; ?>">
                <div class="col-xs-12 col-md-4 text-right">
                    <label class="control-label"><?= $model->getAttributeLabel('pages') ?></label>
                </div>
                <div class="col-xs-12 col-md-8">
                    <table class="infoblocks-items" id="infoblocks_pages">
                        <tbody>
                            <? if (count($model->pages_arr)): ?>
                                <? foreach ($model->pages_arr AS $index => $row): ?>
                                    <?= $this->render('_infoblocks_item', ['model' => $model, 'index' => $index]) ?>
                                <? endforeach; ?>
                            <? else: ?>
                                <?= $this->render('_infoblocks_item', ['model' => $model, 'index' => 0]) ?>
                            <? endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
    
            <div class="form-group">
                <div class="col-xs-12 col-md-offset-4 col-md-8">
                    <div class="m-b-5">Итоговый вид инфоблока</div>
                    <div class="hidden" id="infoblocks_content">
                        <strong id="infoblocks_title"><?= Html::encode($model->title) ?></strong>
                        <ul id="infoblocks_pages_result" class="m-none"></ul>
                    </div>
                </div>
            </div>
    
            <div class="separator"></div>
            <?= $form->field($model, 'status')->checkbox(['class' => 'ichecks', 'label' => 'Опубликовать на страницах']) ?>
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

<table id="tmp_infoblocks" class="hidden">
    <tbody><?= $this->render('_infoblocks_item', ['model' => $model, 'index' => '{index}']) ?></tbody>
</table>

<?php
$this->registerJs("
set_infoblocks_pages();

var last_infoblocks_pages = " . count($model->pages_arr) . ";

$('#infoblocks_pages').on('click', '.btn-add', function(){
    last_infoblocks_pages++;
    
    var content = $('#tmp_infoblocks tbody').html().replace(/{index}/g, last_infoblocks_pages);
    $('#infoblocks_pages tbody').append(content);
    
    set_select2('#infoblocks_pages .select2:last');
});

$('#infoblocks_pages').on('click', '.btn-up, .btn-down', function(){

    var \$currentRow = $(this).closest('.infoblocks-rows');
    var \$otherRow;
    
    if ($(this).hasClass('btn-up')) {
        \$otherRow = $(this).closest('.infoblocks-rows').prev();
    } else {
        \$otherRow = $(this).closest('.infoblocks-rows').next();
    }
    
    var currentHtml = \$currentRow.html();
    
    \$currentRow.html(\$otherRow.html());
    \$otherRow.html(currentHtml);
    
    // remove current
    $('.select2-container', \$currentRow).remove();
    $('.select2-container', \$otherRow).remove();
    
    // init new
    set_select2( \$currentRow.find('.select2') );
    set_select2( \$otherRow.find('.select2') );
    
    set_infoblocks_pages();
});

$('#infoblocks_pages').on('click', '.btn-del', function(){
    $(this).closest('.infoblocks-rows').remove();
    set_infoblocks_pages();
});

$('#" . Html::getInputId($model, 'title') . "').on('keyup', function(){
    var \$info_title = $('#infoblocks_title');
    
    if (this.value == '') {
        \$info_title.addClass('hidden');        
    } else {
        \$info_title.removeClass('hidden').text(this.value);
        $('#infoblocks_title').removeClass('hidden');
    }
}).keyup();
");
