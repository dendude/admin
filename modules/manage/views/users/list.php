<?php

use yii\grid\GridView;
use yii\helpers\Html;
use app\helpers\Statuses;
use app\models\Users;
use app\helpers\Normalize;
use app\helpers\MHtml;
use app\helpers\ManageList;
use app\models\Base;
use app\models\Orders;
use app\models\Reviews;

/** @var $dataProvider \yii\data\DataProviderInterface */
/** @var $searchModel \yii\base\Model */

$action = \app\modules\manage\controllers\UsersController::LIST_NAME;
$this->title = $action;
$this->params['breadcrumbs'] = [
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
                        'class' => 'text-center',
                        'width' => 80,
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ]
                ],
                [
                    'attribute' => 'role',
                    'headerOptions' => [
                        'class' => 'text-center',
                        'width' => 200,
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                    'filter' => Users::getRoles(),
                    'value' => function(Users $model){
                        return $model->getRoleName();
                    },
                ],
                [
                    'attribute' => 'first_name',
                    'headerOptions' => [
                        'class' => 'text-left',
                    ],
                    'contentOptions' => [
                        'class' => 'text-left'
                    ],
                    'value' => function(Users $model){
                        return $model->getFullName();
                    },
                ],
                [
                    'attribute' => 'email',
                    'headerOptions' => [
                        'class' => 'text-left',
                    ],
                    'contentOptions' => [
                        'class' => 'text-left'
                    ],
                ],
                [
                    'attribute' => 'phone',
                    'headerOptions' => [
                        'class' => 'text-center',
                        'width' => 150,
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                    'value' => function(Users $model){
                        return Normalize::formatPhone($model->phone);
                    },
                ],
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'filter' => Statuses::statuses(Statuses::TYPE_ACTIVE),
                    'value' => function($model){
                        return Statuses::getFull($model->status, Statuses::TYPE_ACTIVE);
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
                    'format' => 'ntext',
                    'filter' => false,
                    'value' => function($model){
                        return Normalize::getFullDateByTime($model->modified, PHP_EOL);
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
                        return ManageList::get($model, ['show', 'edit', 'delete']);
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