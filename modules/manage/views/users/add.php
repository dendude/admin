<?php
use yii\helpers\Html;
use yii\widgets\ActiveForm;
use app\models\Users;
use app\models\Projects;

/** @var $model Users */
/** @var $this \yii\web\View */

$action = $model->id ? 'Редактирование пользователя' : 'Добавление пользователя';
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => \app\modules\manage\controllers\UsersController::LIST_NAME, 'url' => ['list']],
    ['label' => $action]
];

$form = ActiveForm::begin();

$w100 = ['inputOptions' => ['class' => 'form-control w-100']];
$w200 = ['inputOptions' => ['class' => 'form-control w-200']];
$w500 = ['inputOptions' => ['class' => 'form-control w-500']];
?>
<div class="w-1000">
    <?= $form->errorSummary($model, ['class' => 'alert alert-danger']); ?>
    <? \app\helpers\MHtml::alertMsg(); ?>
    <div class="box box-primary">
        <div class="box-header with-border"><?= Yii::$app->params['required_fields'] ?></div>
        <div class="box-body">
            <?= $form->field($model, 'role', $w200)->dropDownList(Users::getRoles(), ['prompt' => '']) ?>
    
            <div class="form-group projects-row m-t-20">
                <div class="col-xs-12 col-md-4 text-right">
                    <label><?= $model->getAttributeLabel('projects_arr') ?></label>
                    <?= Html::error($model, 'projects_arr', ['class' => 'help-block']); ?>
                </div>
                <div class="col-xs-12 col-md-8">
                    <?= Html::activeCheckboxList($model, "projects_arr", Projects::getFilterList(), [
                        'class' => 'ichecks',
                        'unselect' => null,
                        'item' => function($index, $label, $name, $checked, $value) use ($model) {
                            $name = str_replace('[]', "[{$index}]", $name);
                            return Html::tag('div', Html::checkbox($name, $checked, [
                                'label' => $label,
                                'value' => $value,
                                'class' => 'ichecks'
                            ]), ['class' => 'checkbox-list']);
                        }
                    ]); ?>
                </div>
            </div>
    
            <div class="separator"></div>
    
            <?= $form->field($model, 'last_name', $w500) ?>
            <?= $form->field($model, 'first_name', $w500) ?>
            <?= $form->field($model, 'sur_name', $w500) ?>

            <div class="separator"></div>
            
            <?= $form->field($model, 'phone', $w200) ?>
    
            <div class="separator"></div>
            
            <?= $form->field($model, 'email', $w500) ?>
            <?= $form->field($model, 'orig_pass', $w500)->passwordInput([
                'placeholder' => $model->isNewRecord ? '' : 'Оставьте поле пустым для сохранения старого пароля',
                'value' => ''
            ]) ?>

            <div class="separator"></div>
    
            <?= $form->field($model, 'status')->checkbox(['class' => 'ichecks', 'label' => 'Активировать пользователя']) ?>
            
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
<?php
$this->registerJs("
$('#" . Html::getInputId($model, 'role') . "').on('change', function(){
    if (this.value == '" . Users::ROLE_ADMIN . "') {
        $('.projects-row').hide();
    } else {
        $('.projects-row').show();
    }
}).change();
");
