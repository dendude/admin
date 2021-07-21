<?php

use yii\helpers\Url;
use yii\helpers\Html;

use app\models\forms\UploadForm;
use app\models\Projects;
use app\models\Pages;

/**
 * @var $this \yii\web\View
 * @var \yii\base\Model $model
 * @var string $field
 * @var string $imageUploadUrl
 * @var string $filesUploadUrl
 * @var string $filesManagerUrl
 * @var Pages[] $pages
 */

$upload_form = new UploadForm();
$project = Projects::getCurrentModel();

$pages = Pages::find()->managed()->byCurrentProject()->orderBy('title ASC')->all();
$pages_list = [];

if ($pages) {
    foreach ($pages AS $p) $pages_list[] = [
        'text' => Html::encode($p->title),
        'href' => $p->full_url,
        'data-id' => $p->id,
        //'target' => '_blank',
        //'rel' => 'nofollow'
    ];
}

$this->registerJs("
    $.FroalaEditor.DefineIcon('alert', {NAME: 'square-o'});
    $.FroalaEditor.RegisterCommand('alert', {
        title: 'Вставить инфоблок',
        focus: true,
        undo: true,
        refreshAfterCallback: false,
        callback: function () {
            this.html.insert('<ul class=\"infoblocks infoblocks-1\"><li class=\"info-title\"><strong>Заголовок инфоблока</strong></li><li><a href=\"#\">Ссылка 1</a></li><li><a href=\"#\">Ссылка 2</a></li></ul>');
        }
    });
");

echo froala\froalaeditor\FroalaEditorWidget::widget([
    'model' => $model,
    'attribute' => $field,
    'clientOptions'=>[
        'toolbarInline'=> false,
        'theme' => 'default', // optional: dark, red, gray, royal
        'language' => 'ru',
        
        'toolbarButtons' => ['fullscreen', 'bold', 'italic', 'underline', 'strikeThrough', 'subscript', 'superscript', 'fontFamily', 'fontSize', '|',
                             'color', 'emoticons', 'inlineStyle', 'paragraphStyle', '|',
                             'paragraphFormat', 'align', 'formatOL', 'formatUL', 'outdent', 'indent', 'quote', 'insertHR', '-',
                             'insertLink', 'insertImage', 'insertVideo', 'insertFile', 'insertTable', '|', 'alert', '|', 'undo', 'redo', '|', 'clearFormatting', 'selectAll', 'html'],
        
        'height' => false,
        'fullPage' => false,
        'heightMin' => 300,
        'heightMax' => 1600,
        
        'htmlRemoveTags' => ['style'],
        'htmlSimpleAmpersand' => true,
        'pastePlain' => true,
        'linkAutoPrefix' => '',
        
        'linkList' => $pages_list,
        
        'tableCellStyles' => [
            'border-none' => 'Без границы',
            'fr-highlighted' => 'Выделить',
            'fr-thick' => 'Толщина',
        ],
        
        'saveInterval' => 10000,
        'saveMethod' => 'POST',
        'saveURL' => Url::to(['save-temp-content']),
        'saveParam' => 'content',
        'saveParams' => [
            'id' => $model->id,
            Yii::$app->request->csrfParam => Yii::$app->request->csrfToken,
        ],
        
        'linkStyles' => [
            'link-color-blue' => 'Синий',
            'link-color-red' => 'Красный',
            'link-color-green' => 'Зеленый',
            'link-color-black' => 'Черный',
            'link-color-gray' => 'Серый',
        ],

        'paragraphStyles' => [
            'clearfix' => 'Не обтекать текстом',
            'pull-left' => 'Обтекать текстом справа',
            'pull-right' => 'Обтекать текстом слева',
        ],
        
        'fileAllowedTypes' => ['application/pdf', 'application/msword', 'application/msexcell'],
        'fileMaxSize' => (50 * pow(1024, 2)),
        'fileUploadMethod' => 'POST',
        'fileUploadParam' => Html::getInputName($upload_form, 'docFile[0]'),
        'fileUploadParams' => [
            Yii::$app->request->csrfParam => Yii::$app->request->csrfToken,
        ],
        'fileUploadURL' => $filesUploadUrl,
        'fileUseSelectedText' => true,
        
        'videoDefaultAlign' => 'center',
        'videoDefaultDisplay' => 'block',
        
        'imageOutputSize' => true,
        'imageMaxSize' => (10 * pow(1024, 2)),
        'imageMinWidth' => 32,
        'imageMinHeight' => 32,
        'imageDefaultWidth' => 0,
        'imageMove' => true,
        'imageAllowedTypes' => ['jpeg', 'jpg', 'png', 'gif', 'svg', 'webp'],
        'imageDefaultAlign' => 'left',
        'imageDefaultDisplay' => 'block',
        'imageUploadURL' => $imageUploadUrl,
        'imageUploadMethod' => 'POST',
        'imageUploadParam' => Html::getInputName($upload_form, 'imgFile[0]'),
        'imageUploadParams' => [
            Yii::$app->request->csrfParam => Yii::$app->request->csrfToken,
        ],
        
        'imageStyles' => [
            'm-l-20' => 'Отступ слева',
            'm-r-20' => 'Отступ справа',
            'm-t-20' => 'Отступ сверху',
            'm-b-20' => 'Отступ снизу',
            
            'border-radius-2' => 'Скругленные углы 2px',
            'border-radius-4' => 'Скругленные углы 4px',
            'border-radius-6' => 'Скругленные углы 6px',
            'border-radius-8' => 'Скругленные углы 8px',
            'border-radius-10' => 'Скругленные углы 10px',

            'border-1' => 'Обводка 1px',
            'border-2' => 'Обводка 2px',
            'border-3' => 'Обводка 3px',
            'border-5' => 'Обводка 5px',
            'border-8' => 'Обводка 8px',
        ],
    ]
]);

$this->registerJs("
    $(document).ready(function(){
        $('a[href*=\"froala.com\"]').closest('div').remove();
    });
");