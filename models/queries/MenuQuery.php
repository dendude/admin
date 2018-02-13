<?php

namespace app\models\queries;

use app\helpers\Statuses;
use app\models\Menu;
use app\models\Projects;
use app\models\traits\CommonQueryTrait;
use Yii;
use yii\db\ActiveQuery;

class MenuQuery extends ActiveQuery
{
    use CommonQueryTrait;
    
    public function byParent($parent_id) {
        $this->andWhere(['parent_id' => $parent_id]);
        return $this;
    }

    public function root() {
        return $this->byParent(0);
    }
}