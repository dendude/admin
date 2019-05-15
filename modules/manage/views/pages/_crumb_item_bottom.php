<?php
use yii\helpers\Html;
use app\models\Pages;
use yii\helpers\ArrayHelper;

/** @var $model Pages */
/** @var $index string */

$pages = Pages::find()->managed()->byCurrentProject()->all();
$data = ($pages ? ArrayHelper::map($pages, 'id', 'full_url') : []);
?>
<div class="form-group">
    <div class="col-xs-12 col-md-6">
        <?= Html::activeDropDownList($model, "breads_bottom_arr[{$index}][page_id]", Pages::getFilterList(), [
            'class' => 'form-control select2-page',
            'onchange' => 'set_crumb_bottom(this)',
            'data-urls' => json_encode($data),
            'prompt' => ' - Страница - '
        ]) ?>
    </div>
    <div class="col-xs-12 col-md-6">
        <?= Html::activeTextInput($model, "breads_bottom_arr[{$index}][name]", [
            'class' => 'form-control',
            'placeholder' => 'Текст крошки'
        ]) ?>
    </div>
</div>