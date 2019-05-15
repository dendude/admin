<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\modules\manage\controllers\PagesController;
use \app\models\Pages;
use yii\helpers\Url;
use app\models\Projects;

/** @var $model Pages */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование страницы' : 'Создание страницы';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => PagesController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$form = ActiveForm::begin();
echo Html::activeHiddenInput($model, 'id');

$w200 = ['inputOptions' => ['class' => 'form-control w-200']];
$w300 = ['inputOptions' => ['class' => 'form-control w-300']];

$project = Projects::getCurrentModel();
?>
<div class="w-1200">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <?= \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body p-t-20 p-b-20">
            <?= $form->field($model, 'title') ?>
            <?= \app\helpers\MHtml::aliasField($model, 'alias', 'alias') ?>
            <div class="row row-comment">
                <div class="col-xs-offset-4 col-xs-8">
                    Пример: вводим "раздел/название", клик "Получить URL" покажет "razdel/nazvanie".<br/>
                    После сохранения ссылка на страницу будет такой: "razdel/nazvanie.html".
                </div>
            </div>
            
            <div class="separator"></div>
    
            <div class="form-group">
                <div class="col-xs-12 col-md-4 text-right">
                    <label class="control-label">
                        <?= $model->getAttributeLabel('breads_top') ?>
                    </label>
                </div>
                <div class="col-xs-12 col-md-8">
                    <div id="breads_top">
                        <? for ($i = 0; $i < 5; $i++): ?>
                            <?= $this->render('_crumb_item_top', ['model' => $model, 'index' => $i]) ?>
                        <? endfor; ?>
                    </div>
                </div>
            </div>
            
            <?= $form->field($model, 'bread_name')->textInput([
                'placeholder' => 'Текст хлебной крошки для текущей страницы'
            ])->label('') ?>

            <div class="form-group p-t-10 p-b-5">
                <div class="col-xs-12 col-md-4 text-right">
                    <label class="control-label">
                        <?= $model->getAttributeLabel('breads_bottom') ?>
                    </label>
                </div>
                <div class="col-xs-12 col-md-8">
                    <div id="breads_bottom">
                        <? for ($i = 0; $i < 5; $i++): ?>
                            <?= $this->render('_crumb_item_bottom', ['model' => $model, 'index' => $i]) ?>
                        <? endfor; ?>
                    </div>
                </div>
            </div>

            <?= $form->field($model, 'meta_t')->textarea(['maxlength' => true]) ?>
            <?= $form->field($model, 'meta_d')->textarea(['maxlength' => true]) ?>
            <?= $form->field($model, 'meta_k')->textarea(['maxlength' => true]) ?>
    
            <div class="separator"></div>
            <?= $form->field($model, 'video_src')->textarea(['placeholder' => 'Ссылка необходима для получения превью видео']) ?>
            <div class="row row-comment">
                <div class="col-xs-offset-4 col-xs-8">
                    Пример: https://www.youtube.com/embed/KlC94cCzliI<br/>
                    или: https://www.youtube.com/watch?v=KlC94cCzliI<br/>
                    или: https://youtu.be/KlC94cCzliI
                </div>
            </div>
            <div class="separator"></div>
            
            <?= $form->field($model, 'is_shared')->checkbox(['class' => 'ichecks']) ?>
            <?= $form->field($model, 'is_sitemap')->checkbox(['class' => 'ichecks']) ?>
            
            <div class="separator"></div>

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
// для показа кол-ва символов у редактируемой страницы
$this->registerJs("
$('textarea').on('keyup', function(){
    charsCalculate(this);
}).keyup();
");
