<?php

use yii\grid\GridView;
use yii\helpers\Html;
use app\helpers\Statuses;
use app\models\Users;
use app\models\Actions;
use app\helpers\Normalize;
use app\helpers\MHtml;
use app\helpers\ManageList;

/** @var $dataProvider \yii\data\DataProviderInterface */
/** @var $searchModel Actions */

$action = \app\modules\manage\controllers\ActionsController::LIST_NAME;
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => $action]
];
?>
<?= MHtml::alertMsg(); ?>
<?= Html::a('Типы событий', ['add'], ['class' => 'btn btn-primary btn-flat m-b-15']); ?>

<div class="box box-success">
    <div class="box-body">
    <?
        echo GridView::widget([
            'dataProvider' => $dataProvider,
            'filterModel' => $searchModel,
            'columns' => [
                [
                    'attribute' => 'id',
                    'format' => 'integer',
                    'headerOptions' => [
                        'width' => 80,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ]
                ],
                [
                    'attribute' => 'manager_id',
                    'format' => 'text',
                    'filter' => Users::getManagersFilter(),
                    'value' => function(Actions $model){
                        return $model->manager->getFullName();
                    },
                    'headerOptions' => [
                        'class' => 'text-center',
                        'width' => 220
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                ],
                [
                    'attribute' => 'type_id',
                    'headerOptions' => [
                        'class' => 'text-left'
                    ],
                    'filter' => \app\models\ActionsTypes::getFilterList(),
                    'value' => function(Actions $model){
                        return $model->type->name;
                    },
                ],
                [
                    'attribute' => 'info',
                    'format' => 'html',
                    'headerOptions' => [
                        'class' => 'text-left'
                    ],
                    'value' => function(Actions $model){
                        return '<pre style="white-space: normal">' . print_r($model->getInfo(), 1) . '</pre>';
                    },
                ],
                [
                    'attribute' => 'created',
                    'format' => 'html',
                    'filter' => false,
                    'value' => function($model){
                        return Normalize::getFullDateByTime($model->created, '<br/>');
                    },
                    'headerOptions' => [
                        'width' => 120,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ]
                ],
            ],
        ]);
    ?>
    </div>
</div>