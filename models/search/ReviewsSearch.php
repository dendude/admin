<?php
namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Reviews;

class ReviewsSearch extends Reviews {

    public function rules()
    {
        return [
            [['id', 'manager_id', 'created', 'modified', 'ordering', 'status'], 'integer'],
            [['user_name', 'user_email', 'user_review'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = parent::find()->managed()->byCurrentProject();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' => [
                'defaultOrder' => [
                    'ordering' => SORT_ASC,
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
        $query->andFilterWhere(['ordering' => $this->ordering]);
        $query->andFilterWhere(['status' => $this->status]);
        
        $query->andFilterWhere(['like', 'user_name', $this->user_name]);
        $query->andFilterWhere(['like', 'user_email', $this->user_email]);
        $query->andFilterWhere(['like', 'user_review', $this->user_review]);

        return $dataProvider;
    }
}