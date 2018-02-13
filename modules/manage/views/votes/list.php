<?php

use yii\grid\GridView;
use yii\helpers\Html;
use app\models\Users;
use app\models\Infoblocks;
use app\helpers\Normalize;
use app\helpers\MHtml;
use app\helpers\ManageList;
use app\helpers\Statuses;
use app\models\Votes;
use app\models\VotesAnswers;

/** @var $dataProvider \yii\data\DataProviderInterface */
/** @var $searchModel Infoblocks */

$action = \app\modules\manage\controllers\VotesController::LIST_NAME;
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => $action]
];
?>
<?= MHtml::alertMsg(); ?>
<?= Html::a('Добавить голосование', ['add'], ['class' => 'btn btn-primary btn-flat m-b-15']); ?>
<?= Html::a('Варианты ответов', ['votes-variants/list'], ['class' => 'btn btn-info btn-flat m-b-15 m-l-15']); ?>

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
                    'value' => function(Votes $model){
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
                    'attribute' => 'name',
                    'headerOptions' => [
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
                    'label' => 'Статистика',
                    'format' => 'raw',
                    'headerOptions' => [
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                    'value' => function(Votes $model){
                        /** @var $models VotesAnswers[] */
                        $models = VotesAnswers::find()->select('variant_id')->where(['vote_id' => $model->id])->distinct()->all();
                        $count = VotesAnswers::find()->where(['vote_id' => $model->id])->count();
                        
                        $return = [];
                        
                        foreach ($models AS $m) {
                            $countTmp = VotesAnswers::find()->where(['variant_id' => $m->variant_id])->count();
                            $percent = round($countTmp * 100 / $count, 2);
                            
                            $return[] = Html::tag('span', $countTmp, [
                                'style' => "height: {$percent}%",
                                'title' => "{$m->variant->title}: {$percent}%",
                            ]);
                        }
                        
                        return Html::tag('div', implode('', $return), ['class' => 'vote-stat']);
                    },
                ],
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'filter' => Statuses::statuses(),
                    'value' => function(Votes $model){
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
                        return ManageList::get($model, ['show', 'edit', 'delete']);
                    },
                    'headerOptions' => [
                        'width' => 120,
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