<?php
use yii\helpers\Html;
use app\models\Pages;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

/** @var $model Pages */
/** @var $index string */

$pages = Pages::find()->managed()->byCurrentProject()->all();
$data = ($pages ? ArrayHelper::map($pages, 'id', 'full_url') : []);
?>
<tr class="infoblocks-rows">
    <td class="p-r-10" width="40%">
        <?= Html::activeDropDownList($model, "pages_arr[{$index}][page_id]", Pages::getFilterList(), [
            'class' => 'form-control select2',
            'onchange' => 'set_infoblocks_pages(this)',
            'data-urls' => json_encode($data),
            'prompt' => ' - Страница - '
        ]) ?>
    </td>
    <td class="p-r-10">
        <?= Html::activeTextInput($model, "pages_arr[{$index}][name]", [
            'class' => 'form-control',
            'onkeyup' => 'set_infoblocks_pages()',
            'placeholder' => 'Текст ссылки'
        ]) ?>
    </td>
    <td width="90" class="p-r-10">
        <div class="btn-group btn-group-justified">
            <div class="btn-group">
                <button class="btn btn-up" type="button"><i class="fa fa-chevron-up"></i></button>
            </div>
            <div class="btn-group">
                <button class="btn btn-down" type="button"><i class="fa fa-chevron-down"></i></button>
            </div>
        </div>
    </td>
    <td width="40">
        <button class="btn btn-success btn-add" type="button"><i class="fa fa-plus"></i></button>
        <button class="btn btn-danger btn-del" type="button"><i class="fa fa-minus"></i></button>
    </td>
</tr>