<?php
use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;

/* @var $this \yii\web\View */
/* @var $content string */

\app\modules\manage\assets\ManageAsset::register($this);

$project = \app\models\Projects::getCurrentModel();
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>"/>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport"/>
    <?= Html::csrfMetaTags() ?>
    
    <!--[if lt IE 9]>
    <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
    
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body>

<?php $this->beginBody() ?>

<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">
    
    <header class="main-header">
        <?= $this->render('_header') ?>
    </header>
    <!-- Left side column. contains the logo and sidebar -->
    
    <?= $this->render('_sidebar') ?>
    
    <!-- Content Wrapper. Contains page content -->
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <h1>
                <?= $this->title ?>
                <? if (isset($this->params['title'])): ?>
                    <small><?= $this->params['title'] ?></small>
                <? endif; ?>
            </h1>
        </section>
        
        <? if (isset($this->params['breadcrumbs'])): ?>
            <div class="box box-widget m-t m-b-xs">
                <?= Breadcrumbs::widget([
                    'tag' => 'ol',
                    'options' => ['class' => 'breadcrumb m-b-xs no-bg bg-white'],
                    'encodeLabels' => false,
                    'homeLink' => ['label' => '<i class="fa fa-home"></i>&nbsp;&nbsp;Главная', 'url' => ['main/index']],
                    'links' => $this->params['breadcrumbs']
                ]); ?>
            </div>
        <? endif; ?>
        
        <!-- Main content -->
        <section class="content">
            <?= $content ?>
        </section>
        <!-- /.content -->
    </div>
    <!-- /.content-wrapper -->
    <footer class="main-footer">
        <div class="pull-right hidden-xs"><b>Version</b> 2.3.2</div>
        <strong>Copyright &copy; 2014-2015 <a href="http://almsaeedstudio.com" target="_blank">Almsaeed Studio</a>.</strong> All rights reserved.
    </footer>
</div>
<!-- ./wrapper -->

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
