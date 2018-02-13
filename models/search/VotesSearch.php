<?php
namespace app\models\search;

use app\models\Votes;
use Yii;
use yii\data\ActiveDataProvider;

class VotesSearch extends Votes {

    public function rules()
    {
        return [
            [['id', 'manager_id', 'created', 'modified', 'status'], 'integer'],
            [['title', 'name'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = Votes::find()->managed()->byCurrentProject();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'modified' => SORT_DESC,
                    'created' => SORT_DESC,
                ],
            ],
            'pagination' => [
                'pageSize' => Yii::$app->params['adminItemsPerPage'],
            ]
        ]);

        if (!$this->load($params)) return $dataProvider;
        
        $query->andFilterWhere(['id' => $this->id]);
        $query->andFilterWhere(['manager_id' => $this->manager_id]);
        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['like', 'name', $this->title]);
        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}