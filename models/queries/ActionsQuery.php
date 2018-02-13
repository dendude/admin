<?php
namespace app\models\queries;

use app\helpers\Normalize;
use app\models\traits\CommonQueryTrait;
use yii\db\ActiveQuery;

class ActionsQuery extends ActiveQuery
{
    use CommonQueryTrait;
    
    public function byManager($id) {
        return $this->andWhere(['manager_id' => $id]);
    }
    
    public function byProject($id) {
        if ($id) $this->andWhere(['project_id' => $id]);
        return $this;
    }
    
    public function unique($is_unique = false) {
        if ($is_unique) $this->indexBy('object_id')->distinct();
        
        return $this;
    }
    
    public function byPeriod($date_from = null, $date_to = null) {
        
        if ($date_from) {
            $date_from = Normalize::getSqlDate($date_from);
            $this->andWhere('created >= :date_from', [':date_from' => strtotime($date_from . ' 00:00:00')]);
        }
        
        if ($date_to) {
            $date_to = Normalize::getSqlDate($date_to);
            $this->andWhere('created <= :date_to', [':date_to' => strtotime($date_to . ' 23:59:59')]);
        }
    
        return $this;
    }
    
    public function byType($id) {
        return $this->andWhere(['type_id' => $id]);
    }
}
