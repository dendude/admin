<?php
use yii\helpers\Html;
use app\models\forms\StatsManagersForm;
use app\models\Users;
use app\models\Actions;

/**
 * @var $model StatsManagersForm
 */

$action = \app\modules\manage\controllers\StatsController::LIST_NAME;
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => $action]
];
?>
<div class="box box-default">
    <div class="box-header with-border">
        <h3 class="box-title">Фильтры</h3>
    </div>
    <div class="box-body">
        <form method="GET" class="form-horizontal">
            <div class="form-group">
                <div class="col-xs-12 col-md-3">
                    <?= Html::activeDropDownList($model, 'project_id', \app\models\Projects::getFilterList(), [
                        'prompt' => '- Выбор проекта -',
                        'class' => 'form-control'
                    ]) ?>
                </div>
                <div class="col-xs-12 col-md-3">
                    <?= Html::activeDropDownList($model, 'agent_id', Users::getManagersFilter(), [
                        'prompt' => '- Выбор менеджера -',
                        'class' => 'form-control'
                    ]) ?>
                </div>
                <div class="col-xs-12 col-md-3">
                    <div class="input-group">
                        <span class="input-group-addon">Дата с</span>
                        <?= Html::activeTextInput($model, 'date_from', [
                            'class' => 'form-control datepickers'
                        ]) ?>
                        <span class="input-group-addon">по</span>
                        <?= Html::activeTextInput($model, 'date_to', [
                            'class' => 'form-control datepickers'
                        ]) ?>
                    </div>
                </div>
                <div class="col-xs-12 col-md-2">
                    <button class="btn btn-primary btn-block">Поиск</button>
                </div>
                <div class="col-xs-12 col-md-1">
                    <a href="<?= \yii\helpers\Url::to(['index']) ?>" class="btn btn-default btn-block">Сброс</a>
                </div>
            </div>
        </form>
    </div>
</div>


<div class="box box-success">
    <div class="box-body">
        <table class="table table-bordered table-striped table-condensed">
            <tr>
                <th class="text-left" rowspan="2">Менеджер</th>
                <th class="text-center" colspan="6">Показатели</th>
            </tr>
            <tr>
                <td class="text-center">Новых страниц</td>
                <td class="text-center">Изменено страниц</td>
                <td class="text-center">Новых новостей</td>
                <td class="text-center">Изменено новостей</td>
                <td class="text-center">Новых отзывов</td>
                <td class="text-center">Изменено отзывов</td>
            </tr>
            <? $unique_query = 'DISTINCT(object_id)'; ?>
            
            <? foreach (Users::getManagersFilter() AS $user_id => $user_name): ?>
                <? if ($model->agent_id && $model->agent_id != $user_id) continue; ?>
                <tr>
                    <td class="text-left"><?= Html::encode($user_name) ?></td>
                    <? for ($i = 1; $i <= 6; $i++): ?>
                        <? $query = Actions::find()->byProject($model->project_id)->byManager($user_id)->byPeriod($model->date_from, $model->date_to); ?>
                        <td class="text-center">
                            <?= $query->byType($i)->count() ?>&nbsp;(<abbr title="уникальных"><?= $query->byType($i)->count($unique_query) ?></abbr>)
                        </td>
                    <? endfor; ?>
                </tr>
            <? endforeach; ?>
        </table>
    </div>
</div>