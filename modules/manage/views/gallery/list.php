<?php
use app\modules\manage\controllers\GalleryController;
use yii\helpers\Html;
use \app\models\Gallery;

$action = GalleryController::LIST_NAME;
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => $action]
];
?>
<div class="w-900">
    <?= Html::a('Добавить', ['add'], ['class' => 'btn btn-primary btn-add m-b']); ?>
    <div class="clearfix"></div>
    
    <? \app\helpers\MHtml::alertMsg(); ?>
    
    <div class="box box-primary">
        <div class="box-body">
            <?= \app\helpers\MenuHelper::getGalleryContent() ?>
        </div>
    </div>
</div>
<?
$this->registerJs("
$(document).on('click', '.menu-item a', function(e){
    var \$this = $(this);

    if (\$this.hasClass('btn-act')) {
        // just url
    } else {
        e.preventDefault();
        $.ajax({
            url: \$this.attr('href'),
            dataType: 'html',
            beforeSend: function(){
                loader.show(\$this.closest('.box'));
            },
            success: function(result) {
                $('section.content').html(result);
            }
        });
    }
});
");
?>