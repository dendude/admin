<?php

use yii\grid\GridView;
use yii\helpers\Html;
use app\models\Users;
use app\models\Reviews;
use app\helpers\Normalize;
use app\helpers\MHtml;
use app\helpers\ManageList;
use app\helpers\Statuses;
use app\models\forms\UploadForm;

/** @var $dataProvider \yii\data\DataProviderInterface */
/** @var $searchModel Reviews */

$action = \app\modules\manage\controllers\ReviewsController::LIST_NAME;
$this->title = $action;
$this->params['breadcrumbs'] = [
    ['label' => $action]
];

$settings = UploadForm::getConfig();
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
                    'attribute' => 'img_base',
                    'format' => 'raw',
                    'value' => function(Reviews $model) use ($settings) {
        
                        $img_logo = "/{$settings['symlink']}/images/default-logo-reviews.png";
                        if ($model->img_base) $img_logo = "/{$settings['symlink']}/images/{$model->img_base}";
                        if ($model->img_logo) $img_logo = UploadForm::getSrc($model->img_logo, UploadForm::TYPE_REVIEWS, '_sm');
        
                        return Html::img($img_logo);
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
                    'attribute' => 'img_name',
                    'format' => 'raw',
                    'value' => function(Reviews $model){
                        return Html::a(
                            Html::img(UploadForm::getSrc($model->img_name, UploadForm::TYPE_REVIEWS, '_sm')),
                            UploadForm::getSrc($model->img_name, UploadForm::TYPE_REVIEWS),
                            ['target' => '_blank', 'encode' => false]
                        );
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
                    'value' => function(Reviews $model){
                        return $model->manager_id ? $model->manager->getFullName() : '';
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
                    'attribute' => 'user_name',
                    'headerOptions' => [
                        'class' => 'text-left'
                    ],
                ],
                [
                    'attribute' => 'user_email',
                    'headerOptions' => [
                        'class' => 'text-left'
                    ],
                ],
                [
                    'attribute' => 'user_review',
                    'headerOptions' => [
                        'class' => 'text-left'
                    ],
                    'value' => function(Reviews $model){
                        return mb_substr($model->user_review, 0, 140, Yii::$app->charset) . '..';
                    },
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
                    'value' => function(Reviews $model){
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