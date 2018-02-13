<?php

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception \yii\web\HttpException */

use yii\helpers\Html;

$message = rtrim($message, '.');
$code = $exception->statusCode ? $exception->statusCode : $exception->getCode();

$this->title = $message;
?>
<div class="site-error m-a m-t-100 w-700">
    <h1>Ошибка #<?= $code ?></h1>
    <div class="alert alert-danger"><?= nl2br(Html::encode($message)) ?></div>
</div>