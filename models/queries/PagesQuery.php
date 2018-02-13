<?php

namespace app\models\queries;
use app\helpers\Statuses;
use app\models\Projects;
use app\models\traits\CommonQueryTrait;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\models\Pages]].
 *
 * @see \app\models\Pages
 */
class PagesQuery extends ActiveQuery
{
    use CommonQueryTrait;
    
    public function ordering()
    {
        return $this->orderBy(['title' => SORT_ASC]);
    }
}
