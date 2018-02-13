<?
use app\models\Projects;
use yii\helpers\Url;

$this->title = 'Главная';

/**
 * @var $s \yii\web\View
 * @var $projects Projects[]
 */

foreach ($projects AS $k => $p) {
    if (!Yii::$app->user->can('project', ['id' => $p->id])) unset($projects[$k]);
}
?>
<div class="admin-default-index">
    <h4>Выберите проект для управления</h4>
    <? if ($projects): ?>
        <div class="row">
        <? foreach ($projects AS $p): ?>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon <? if (Projects::isCurrent($p->id)): ?>bg-green<? endif; ?>">
                        <i class="<?= $p->site_icon ?>"></i>
                    </span>
                    <div class="info-box-content">
                        <span class="info-box-text"><?= $p->site_name ?></span>
                        <span class="info-box-number m-t-5 m-b-5">
                            <a href="<?= Url::to(['project', 'id' => $p->id]) ?>">
                                <? if (Projects::isCurrent($p->id)): ?>
                                    <strong class="text-success">Выбран</strong>
                                <? else: ?>
                                    Управление проектом <i class="fa fa-sign-in m-l-5"></i>
                                <? endif; ?>
                            </a>
                        </span>
                        <a href="<?= $p->site_url ?>" target="_blank">Перейти на сайт &raquo;</a>
                    </div>
                </div>
            </div>
        <? endforeach; ?>
        </div>
    <? else: ?>
        <div class="alert alert-info">Нет доступных проектов</div>
    <? endif; ?>
</div>
