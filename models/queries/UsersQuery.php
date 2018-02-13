<?php

namespace app\models\queries;
use app\helpers\Statuses;
use app\models\traits\CommonQueryTrait;
use yii\db\ActiveQuery;

/**
 * This is the ActiveQuery class for [[\app\models\Users]].
 *
 * @see \app\models\Users
 */
class UsersQuery extends ActiveQuery
{
    use CommonQueryTrait;
    
    public function ordering()
    {
        return $this->orderBy(['id' => SORT_DESC]);
    }
}
