<?php
namespace app\models\queries;

use app\helpers\Statuses;
use app\models\traits\CommonQueryTrait;
use yii\db\ActiveQuery;

class GalleryQuery extends ActiveQuery
{
    use CommonQueryTrait;
    
    public function byParent($parent_id) {
        $this->andWhere(['parent_id' => $parent_id]);
        return $this;
    }

    public function manage() {
        return $this->andWhere('status != :status', [':status' => Statuses::STATUS_REMOVED]);
    }
    
    public function root() {
        return $this->byParent(0);
    }
}
