<?php

use yii\grid\GridView;
use yii\helpers\Html;
use app\models\Users;
use app\models\Infoblocks;
use app\helpers\Normalize;
use app\helpers\MHtml;
use app\helpers\ManageList;
use app\helpers\Statuses;
use app\models\VotesVariants;
use app\modules\manage\controllers\VotesVariantsController;
use app\modules\manage\controllers\VotesController;
use app\models\Votes;

/** @var $dataProvider \yii\data\DataProviderInterface */
/** @var $searchModel Infoblocks */
/** @var $model Votes */

$action = VotesVariantsController::LIST_NAME;
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => VotesController::LIST_NAME, 'url' => ['votes/list']],
    ['label' => $action]
];
?>
<?= MHtml::alertMsg(); ?>
<?= Html::a('Добавить', ['add'], ['class' => 'btn btn-primary btn-flat m-b-15']); ?>

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
                    'value' => function(VotesVariants $model){
                        return $model->manager->getFullName();
                    },
                    'headerOptions' => [
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                ],
                [
                    'attribute' => 'vote_id',
                    'format' => 'text',
                    'filter' => Votes::getFilterList(),
                    'value' => function(VotesVariants $model){
                        return $model->vote->name;
                    },
                    'headerOptions' => [
                        'class' => 'text-left',
                    ],
                    'contentOptions' => [
                        'class' => 'text-left'
                    ],
                ],
                [
                    'attribute' => 'title',
                    'headerOptions' => [
                        'class' => 'text-left'
                    ],
                ],
                [
                    'attribute' => 'ordering',
                    'headerOptions' => [
                        'width' => 80,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ]
                ],
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'filter' => Statuses::statuses(),
                    'value' => function(VotesVariants $model){
                        return Statuses::getFull($model->status);
                    },
                    'headerOptions' => [
                        'width' => 120,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ]
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
                [
                    'attribute' => 'modified',
                    'format' => 'html',
                    'filter' => false,
                    'value' => function($model){
                        return Normalize::getFullDateByTime($model->modified, '<br/>');
                    },
                    'headerOptions' => [
                        'width' => 120,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ]
                ],
                [
                    'header' => 'Действия',
                    'format' => 'raw',
                    'value' => function($model) {
                        return ManageList::get($model, ['edit', 'delete']);
                    },
                    'headerOptions' => [
                        'width' => 100,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ]
                ]
            ],
        ]);
    ?>
    </div>
</div>