<?php
use \yii\helpers\Url;
use app\models\forms\UploadForm;
use app\models\Users;
use yii\helpers\Html;
use app\models\Projects;

$controller_id = Yii::$app->controller->id;
$action_id = Yii::$app->controller->action->id;
$ca = "{$controller_id}/$action_id";

$actives = [$controller_id => 'class="active"'];
$ca_actives = [$ca => 'class="active"'];

// счетчики
$new_camps = 0;
$new_orders = 0;
$new_questions = 0;
$new_reviews = 0;

/**
 * @var $identity Users
 */
$identity = Yii::$app->user->identity;
$project = Projects::findOne(Projects::getCurrent());
?>
<aside class="main-sidebar">
    <section class="sidebar">

        <div class="user-panel">
            <div class="pull-left image">
                <? if ($identity->photo): ?>
                    <img src="<?= UploadForm::getSrc($identity->photo, UploadForm::TYPE_PROFILE, '_sm') ?>" class="img-circle" alt=""/>
                <? else: ?>
                    <img src="/lib/AdminLTE/dist/img/user2-160x160.jpg" class="img-circle" alt=""/>
                <? endif; ?>
            </div>
            <div class="pull-left info">
                <p><?= Html::encode("{$identity->first_name} {$identity->last_name}") ?></p>
                <span><i class="fa fa-circle text-success m-r-10"></i><?= $identity->getRoleName() ?></span>
            </div>
        </div>

        <ul class="sidebar-menu">
            <li class="header"><?= $project ? mb_strtoupper($project->site_name, Yii::$app->charset) : 'Менеджер'; ?></li>
            
            <li <?= @$actives['default'] ?>>
                <a href="<?= Url::to(['main/index']) ?>">
                    <i class="fa fa-home"></i><span>Главная</span>
                </a>
            </li>
            
            <? if (Projects::getCurrent()): ?>
            
            <li <?= @$actives['reviews'] ?>>
                <a href="<?= Url::to(['reviews/list']) ?>">
                    <i class="fa fa-comments"></i><span>Отзывы</span>
                    <? if ($new_reviews): ?>&nbsp;<small class="label bg-red">+<?= $new_reviews ?></small><? endif; ?>
                </a>
            </li>
    
            <li <?= @$actives['faq'] ?>>
                <a href="<?= Url::to(['faq/list']) ?>">
                    <i class="fa fa-comment"></i><span>Вопросы и ответы</span>
                </a>
            </li>
    
            <li class="treeview <?= in_array($controller_id, [
                'news','news-sections','pages','gallery','infoblocks','menu','votes','votes-variants'
            ]) ? 'active' : '' ?>">
                <a href="#">
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left"></i>
                    </span>
                    <i class="fa fa-file-text"></i><span>Содержимое сайта</span>
                </a>
                <ul class="treeview-menu">
                    <li <?= @$actives['menu'] ?>>
                        <a href="<?= Url::to(['menu/list']) ?>">
                            <i class="fa fa-circle-o"></i><span>Меню</span>
                        </a>
                    </li>
                    <li <?= @$actives['gallery'] ?>>
                        <a href="<?= Url::to(['gallery/list']) ?>">
                            <i class="fa fa-circle-o"></i><span>Галереи</span>
                        </a>
                    </li>
                    <li <?= @$actives['pages'] ?>>
                        <a href="<?= Url::to(['pages/list']) ?>">
                            <i class="fa fa-circle-o"></i><span>Страницы</span>
                        </a>
                    </li>
                    <li <?= @$actives['infoblocks'] ?>>
                        <a href="<?= Url::to(['infoblocks/list']) ?>">
                            <i class="fa fa-circle-o"></i><span>Инфоблоки</span>
                        </a>
                    </li>
                    <li <?= @$actives['votes'] ?> <?= @$actives['votes-variants'] ?>>
                        <a href="<?= Url::to(['votes/list']) ?>">
                            <i class="fa fa-circle-o"></i><span>Голосования</span>
                        </a>
                    </li>
                    <li <?= @$actives['news-sections'] ?>>
                        <a href="<?= Url::to(['news-sections/list']) ?>">
                            <i class="fa fa-circle-o"></i><span>Разделы новостей</span>
                        </a>
                    </li>
                    <li <?= @$actives['news'] ?>>
                        <a href="<?= Url::to(['news/list']) ?>">
                            <i class="fa fa-circle-o"></i><span>Новости</span>
                        </a>
                    </li>
                </ul>
            </li>
    
            <li <?= @$actives['sitemap'] ?>>
                <a href="<?= Url::to(['/site/sitemap']) ?>" target="_blank">
                    <i class="fa fa-sitemap"></i><span>Карта сайта</span>
                </a>
            </li>
            
            <? endif; ?>
        </ul>
            
        <? if (Yii::$app->user->can(Users::ROLE_ADMIN)): ?>
            <ul class="sidebar-menu">
                <li class="header">Администратор</li>
        
                <li <?= @$actives['actions'] ?>>
                    <a href="<?= Url::to(['actions/list']) ?>">
                        <i class="fa fa-tasks"></i><span>Журнал действий</span>
                    </a>
                </li>
    
                <li <?= @$actives['stats'] ?>>
                    <a href="<?= Url::to(['stats/index']) ?>">
                        <i class="fa fa-bar-chart"></i><span>Статистика менеджеров</span>
                    </a>
                </li>
        
                <li <?= @$actives['users'] ?>>
                    <a href="<?= Url::to(['users/list']) ?>">
                        <i class="fa fa-user"></i><span>Пользователи</span>
                    </a>
                </li>
    
                <li <?= @$actives['projects'] ?>>
                    <a href="<?= Url::to(['projects/list']) ?>">
                        <i class="fa fa-diamond"></i><span>Проекты</span>
                    </a>
                </li>
    
                <li class="treeview <?= in_array($controller_id, ['mail-settings','mail-templates','payments']) ? 'active' : '' ?>">
                    <a href="#">
                        <span class="pull-right-container">
                            <i class="fa fa-angle-left"></i>
                        </span>
                        <i class="fa fa-cogs"></i><span>Настройки</span>
                    </a>
                    <ul class="treeview-menu">
                        <li <?= @$actives['mail-settings'] ?>>
                            <a href="<?= Url::to(['mail-settings/index']) ?>">
                                <i class="fa fa-circle-o"></i><span>Отправка почты</span>
                            </a>
                        </li>
                        <li <?= @$actives['mail-templates'] ?>>
                            <a href="<?= Url::to(['mail-templates/list']) ?>">
                                <i class="fa fa-circle-o"></i><span>Шаблоны писем</span>
                            </a>
                        </li>
                    </ul>
                </li>
            </ul>
            
        <? endif; ?>

    </section>
</aside>