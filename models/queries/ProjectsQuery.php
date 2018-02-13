<?php
namespace app\models\queries;

use app\helpers\Statuses;
use app\models\traits\CommonQueryTrait;
use yii\db\ActiveQuery;

class ProjectsQuery extends ActiveQuery
{
    use CommonQueryTrait;
}
