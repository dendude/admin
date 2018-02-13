<?php
namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Actions;
use yii\db\ActiveQuery;

class ActionsSearch extends Actions {

    public function rules()
    {
        return [
            [['id', 'manager_id', 'type_id', 'created'], 'integer'],
            [['info'], 'string'],
        ];
    }

    public function search($params)
    {
        /** @var $query ActiveQuery */
        $query = parent::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => Yii::$app->params['adminItemsPerPage'],
            ]
        ]);

        if (!$this->load($params)) return $dataProvider;

        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['manager_id' => $this->manager_id]);
        $query->andFilterWhere(['type_id' => $this->type_id]);

        $query->andFilterWhere(['like', 'info', $this->info]);

        return $dataProvider;
    }
}