<?php
use yii\grid\GridView;
use yii\helpers\Html;
use app\helpers\Statuses;
use app\models\Users;
use app\models\Pages;
use app\helpers\MHtml;
use app\helpers\ManageList;
use app\models\NewsSections;
use app\models\forms\UploadForm;

/** @var $dataProvider \yii\data\DataProviderInterface */
/** @var $searchModel Pages */

$action = \app\modules\manage\controllers\NewsSectionsController::LIST_NAME;
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
                    'value' => function(NewsSections $model){
                        return Html::a(Html::img(UploadForm::getSrc($model->photo, UploadForm::TYPE_NEWS, '_sm'), [
                            'class' => 'max-width-50 max-height-50'// a-slider
                        ]), UploadForm::getSrc($model->photo, UploadForm::TYPE_NEWS), [
                            'title' => 'Смотреть фото',
                            'target' => '_blank',
                            'encode' => false,
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
                    'attribute' => 'manager_id',
                    'format' => 'text',
                    'filter' => Users::getManagersFilter(),
                    'value' => function(NewsSections $model){
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
                    'value' => function(NewsSections $model){
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
                    'header' => 'Действия',
                    'format' => 'raw',
                    'value' => function($model) {
                        return ManageList::get($model);
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