<?php
use yii\helpers\Html;
use app\models\forms\UploadForm;

$upload = new UploadForm();

/** @var $url string */
/** @var $zone_id string */
/** @var $field string */
/** @var $isDeleteBtn bool */
/** @var $custom_data array|null */
/** @var $model \yii\base\Model */
/** @var $max_files integer */

$zone_status = uniqid('dropzone_status_');

// для отображения уже загруженных фотографий
$photos = [];
$f = rtrim($field, '[]');

if (!empty($model->{$f})) {

    switch (true) {
        case ($model instanceof \app\models\Pages) :
            $type = UploadForm::TYPE_PAGES;
            break;
            
        case ($model instanceof \app\models\Reviews) :
            $type = UploadForm::TYPE_REVIEWS;
            break;
    
        case ($model instanceof \app\models\Gallery) :
            $type = UploadForm::TYPE_GALLERY;
            break;
    
        case ($model instanceof \app\models\News) :
        case ($model instanceof \app\models\NewsSections) :
            $type = UploadForm::TYPE_NEWS;
            break;
        
        default:
            $type = UploadForm::TYPE_PAGES;
    }
    
    // фото смотрим по симлинкам, т.к. по прямой ссылке несекьюрно - ругается плагин
    $project_settings = UploadForm::getConfig();
    
    if (is_array($model->{$f})) {
        foreach ($model->{$f} AS $photo) {
            $photos[] = "'" . UploadForm::getSrc($photo, $type) . "'";
        }
    } else {
        $photos[] = "'" . UploadForm::getSrc($model->{$f}, $type) . "'";
    }
}

Yii::$app->view->registerJs("
var timer;
Dropzone.autoDiscover = false;
var myDropZone = new Dropzone('#{$zone_id}', {
    paramName: '" . Html::getInputName($upload, 'imgFile') . "',
    acceptedFiles: 'image/*',
    autoProcessQueue: true,
    uploadMultiple: true,
    parallelUploads: 1,
    
    url: '{$url}',    
    maxFiles: {$max_files},
    maxFilesize: 10, // MB
    
    addRemoveLinks: " . ($isDeleteBtn ? 'true' : 'false') . ",
    
    dictDefaultMessage: 'Кликните или перетяните сюда файл для загрузки',
    dictFallbackMessage: 'Ваш браузер не поддерживает HTML5 загрузку файлов',
    dictFallbackText: 'Воспользуйтесь старым загрузчиком',
    dictInvalidFileType: 'Файл имеет неверное расширение',
    dictFileTooBig: 'Слишком большой файл. Разрешается не более 10МБ',
    dictResponseError: 'Ошибка загрузки файла, сервер не доступен',
    dictCancelUpload: 'Отмена загрузки',
    dictCancelUploadConfirmation: 'Подтверждаете отмену загрузки?',
    dictRemoveFile: 'Удалить',
    dictMaxFilesExceeded: 'Максимальное кол-во загружаемых файлов: {$max_files}',
    
    sending: function(file, xhr, formData){
        if (!formData.get('" . Yii::$app->request->csrfParam . "')) {
            formData.append('" . Yii::$app->request->csrfParam . "', '" . Yii::$app->request->csrfToken . "');
        }
    },
    
    init: function() {        
        if (" . count($photos) . ") {
            
            var photos = [" . implode(',', $photos) . "];
            
            for (var k in photos) {
                var f_name = photos[k].split('/').pop();
                var mockFile = { name: f_name, size: 12345, kind: 'image', accepted: true };
                                       
                this.files.push(mockFile);
                this.emit('addedfile', mockFile);
                this.createThumbnailFromUrl(mockFile, photos[k]);
                this.emit('complete', mockFile);
                
                mockFile.previewElement.classList.add('dz-complete');
                
                var f = $('" . Html::activeHiddenInput($model, $field) . "').val(f_name);
                $('#{$zone_id} .dz-preview:not(.uploaded)').eq(0).append(f).addClass('uploaded');
            }
        }
    },    
    success: function(xhr, res) {                
        if (res.file_name) {            
            var f = $('" . Html::activeHiddenInput($model, $field) . "');
            f.val(res.file_name);
            
            $('#{$zone_id} .dz-preview:not(.uploaded)').eq(0).append(f).addClass('uploaded');
        } else {
            $('#{$zone_status}').html('').removeClass('hidden').show();
            for (var k in res) {
                $('#{$zone_status}').append(res[k].join('<br />') + '<br />');
            }            
            this.removeFile(xhr);
            
            clearTimeout(timer);
            timer = setTimeout(function(){
                $('#{$zone_status}').slideUp();
            }, 5000);
        }
                
        $('#{$zone_id}').closest('.form-group').removeClass('has-error has-success');
        $('#{$zone_id}').closest('.form-group').find('.help-block').html('');
    },
    maxfilesexceeded: function(xhr){
        this.removeFile(xhr);
        
        var w = word_amount({$max_files}, ['фотографий', 'фотографии', 'фотографий'], true);
        $('#{$zone_status}').text('Вы можете загрузить не более ' + w).removeClass('hidden').show();
        
        clearTimeout(timer);
        timer = setTimeout(function(){
            $('#{$zone_status}').slideUp();
        }, 5000);
    }
});
");
?>
<? if (!is_array($model->{$f})): ?>
    <?= Html::activeHiddenInput($model, $field, ['value' => '']) ?>
<? endif; ?>
<div id="<?= $zone_status ?>" class="alert alert-danger hidden"></div>
<div id="<?= $zone_id ?>" class="dropzone"></div>
<?= Html::error($model, $field, ['class' => 'help-block']) ?>