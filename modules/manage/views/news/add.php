<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\NewsController;
use \app\models\News;
use yii\helpers\Url;
use app\models\Projects;
use app\models\NewsSections;

/** @var $model News */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование новости' : 'Создание новости';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => NewsController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$w150 = ['inputOptions' => ['class' => 'form-control w-150']];
$datePicker = ['inputOptions' => ['class' => 'form-control w-150 datepickers']];
$project = Projects::getCurrentModel();

$form = ActiveForm::begin();
?>
<div class="w-1200">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <?= \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body p-t-20 p-b-20">
            <div class="form-group <? if ($model->isAttributeRequired('photo')): ?>required<? endif; ?>">
                <div class="col-xs-12 col-md-4 text-right">
                    <label for="" class="control-label"><?= $model->getAttributeLabel('photo') ?></label>
                </div>
                <div class="col-xs-12 col-md-8">
                    <?= \app\modules\manage\widgets\DropZoneWidget::widget([
                        'model' => $model,
                        'field' => 'photo',
                        'zone_id' => 'photo_zone',
                        'url' => \yii\helpers\Url::to(['upload']),
                    ]); ?>
                </div>
            </div>
            <div class="separator"></div>
            <? if (Projects::hasNewsSections()): ?>
                <?= $form->field($model, 'section_id')->dropDownList(NewsSections::getFilterList()) ?>
            <? endif; ?>
            
            <?= $form->field($model, 'title') ?>
            <?= $form->field($model, 'title_menu') ?>
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
            <?= $form->field($model, 'about')->textarea(['maxlength' => true]) ?>
            <div class="separator"></div>
            <?= $form->field($model, 'meta_t')->textarea(['maxlength' => true]) ?>
            <?= $form->field($model, 'meta_d')->textarea(['maxlength' => true]) ?>
            <?= $form->field($model, 'meta_k')->textarea(['maxlength' => true]) ?>
            <div class="separator"></div>
            <?= $form->field($model, 'ordering', $w150)->input('number', ['step' => 1]) ?>
            <div class="separator"></div>
            <?= $form->field($model, 'is_slider')->checkbox(['class' => 'ichecks']) ?>
            <?= $form->field($model, 'status')->checkbox(['class' => 'ichecks', 'label' => 'Опубликовать на сайте']) ?>
            <div class="separator"></div>
            
            <? if (!$model->isNewRecord): ?>
                <?= $form->field($model, 'pub_date_str', $datePicker) ?>
                <div class="separator"></div>
            <? endif; ?>

            <div class="form-group">
                <div class="col-xs-offset-4 col-xs-2">
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

            <div class="row m-t-30">
                <div class="col-xs-offset-4 col-xs-2">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php ActiveForm::end() ?>

<?php
// для показа кол-ва символов у редактируемой новости
$this->registerJs("
$('textarea').on('keyup', function(){
    charsCalculate(this);
}).keyup();
");
