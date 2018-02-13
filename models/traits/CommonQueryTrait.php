<?php
namespace app\models\traits;

use app\helpers\Statuses;
use app\models\Projects;

/**
 * Class CommonQueryTrait
 *
 * трейт типовых запросов модели
 *
 * @package app\models\traits
 */
trait CommonQueryTrait
{
    public function active()
    {
        $this->andWhere(['status' => Statuses::STATUS_ACTIVE]);
        return $this;
    }
    
    public function managed()
    {
        $this->andWhere('status != :status', [':status' => Statuses::STATUS_REMOVED]);
        return $this;
    }
    
    public function byCurrentProject()
    {
        $this->andWhere(['project_id' => Projects::getCurrent()]);
        return $this;
    }
    
    public function ordering()
    {
        $this->orderBy(['ordering' => SORT_ASC]);
        return $this;
    }
}