<?php
namespace app\models\search;

use app\models\VotesVariants;
use Yii;
use yii\data\ActiveDataProvider;

class VotesVariantsSearch extends VotesVariants {

    public function rules()
    {
        return [
            [['id', 'manager_id', 'vote_id', 'created', 'modified', 'status', 'ordering'], 'integer'],
            [['title'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = VotesVariants::find()->managed()->byCurrentProject();

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
        $query->andFilterWhere(['vote_id' => $this->vote_id]);
        $query->andFilterWhere(['status' => $this->status]);
        $query->andFilterWhere(['ordering' => $this->ordering]);
        $query->andFilterWhere(['like', 'title', $this->title]);

        return $dataProvider;
    }
}