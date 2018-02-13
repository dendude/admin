<?php
use yii\grid\GridView;
use yii\helpers\Html;
use app\helpers\Statuses;
use app\models\Users;
use app\models\Pages;
use app\helpers\Normalize;
use app\helpers\MHtml;
use app\helpers\ManageList;
use app\models\News;
use app\models\forms\UploadForm;

/** @var $dataProvider \yii\data\DataProviderInterface */
/** @var $searchModel News */

$action = \app\modules\manage\controllers\NewsController::LIST_NAME;
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
                        'width' => 80,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ]
                ],
                [
                    'attribute' => 'photo',
                    'format' => 'html',
                    'value' => function(News $model){
                        return Html::img(UploadForm::getSrc($model->photo, UploadForm::TYPE_NEWS), [
                            'class' => 'max-width-50 max-height-50'
                        ]);
                    },
                    'headerOptions' => [
                        'class' => 'text-center',
                        'width' => 50
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                ],
                [
                    'visible' => \app\models\Projects::hasNewsSections(),
                    'attribute' => 'section_id',
                    'format' => 'text',
                    'filter' => \app\models\NewsSections::getFilterList(),
                    'value' => function(News $model){
                        return $model->section ? $model->section->title : null;
                    },
                    'headerOptions' => [
                        'class' => 'text-center',
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                ],
                [
                    'attribute' => 'manager_id',
                    'format' => 'text',
                    'filter' => Users::getManagersFilter(),
                    'value' => function(News $model){
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
                    'attribute' => 'title',
                    'headerOptions' => [
                        'class' => 'text-left'
                    ],
                ],
                [
                    'attribute' => 'alias',
                    'headerOptions' => [
                        'class' => 'text-left'
                    ],
                ],
                [
                    'label' => 'Slider',
                    'attribute' => 'is_slider',
                    'filter' => Statuses::statuses(Statuses::TYPE_YESNO),
                    'value' => function(News $model){
                        return Statuses::getFull($model->is_slider, Statuses::TYPE_YESNO);
                    },
                    'format' => 'raw',
                    'headerOptions' => [
                        'width' => 80,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ],
                ],
                [
                    'attribute' => 'status',
                    'format' => 'html',
                    'filter' => Statuses::statuses(),
                    'value' => function(News $model){
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
                        'width' => 100,
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
                        'width' => 100,
                        'class' => 'text-center'
                    ],
                    'contentOptions' => [
                        'class' => 'text-center'
                    ]
                ],
                [
                    'attribute' => 'pub_date',
                    'format' => 'text',
                    'filter' => false,
                    'value' => function($model){
                        return Normalize::getDate($model->pub_date);
                    },
                    'headerOptions' => [
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