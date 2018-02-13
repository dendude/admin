<?php
use yii\helpers\Html;
use app\models\Pages;
use yii\helpers\ArrayHelper;

/** @var $model Pages */
/** @var $index string */

$pages = Pages::find()->managed()->byCurrentProject()->all();
$data = ($pages ? ArrayHelper::map($pages, 'id', 'full_url') : []);
?>
<tr class="crumb-rows">
    <td class="p-r-10" width="50%">
        <?= Html::activeDropDownList($model, "breads_bottom_arr[{$index}][page_id]", Pages::getFilterList(), [
            'class' => 'form-control select2',
            'onchange' => 'set_crumb_bottom(this)',
            'data-urls' => json_encode($data),
            'prompt' => ' - Страница - '
        ]) ?>
    </td>
    <td class="p-r-10">
        <?= Html::activeTextInput($model, "breads_bottom_arr[{$index}][name]", [
            'class' => 'form-control',
            'onkeyup' => 'set_crumb_bottom()',
            'placeholder' => 'Текст крошки'
        ]) ?>
    </td>
    <td width="40">
        <button class="btn btn-success btn-block btn-add" type="button"><i class="fa fa-plus"></i></button>
        <button class="btn btn-danger btn-block btn-del" type="button"><i class="fa fa-minus"></i></button>
    </td>
</tr>