<?php
use yii\helpers\Html;

/* @var $this yii\web\View */
/* @var $name string */
/* @var $message string */
/* @var $exception \yii\web\HttpException */

$message = rtrim($message, '.');
$code = $exception->statusCode ? $exception->statusCode : $exception->getCode();

$this->title = 'Ошибка ' . $code;
?>
<div class="alert alert-danger"><?= nl2br(Html::encode($message)) ?></div>