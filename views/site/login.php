<?php
use yii\helpers\Url;
use yii\widgets\ActiveForm;

/**
 * @var $this yii\web\View
 * @var $model \app\models\forms\LoginForm
 *
 */

$this->title = 'Вход в панель управления';
?>
<div class="m-a m-t-150 w-350">
    <h1>Вход в Панель Управления</h1>
    
    <div class="auth-form">
        <? $form = ActiveForm::begin([
            'fieldConfig' => Yii::$app->params['fieldConfigAuth']
        ]); ?>
        
        <?= $form->field($model, 'email')->textInput(['placeholder' => $model->getAttributeLabel('email')]) ?>
        <?= $form->field($model, 'pass')->passwordInput(['placeholder' => $model->getAttributeLabel('pass')]) ?>

        <div class="row">
            <div class="col-xs-12">
                <button type="submit" class="btn btn-block btn-primary">Войти</button>
            </div>
        </div>
        
        <? ActiveForm::end(); ?>
    </div>
    
    <div class="m-t-20">
        <a href="<?= Url::to(['password']) ?>" class="btn btn-block btn-link">Забыли пароль?</a>
    </div>
</div>