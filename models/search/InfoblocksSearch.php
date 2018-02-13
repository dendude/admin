<?php
namespace app\models\search;

use Yii;
use yii\data\ActiveDataProvider;
use app\models\Infoblocks;

class InfoblocksSearch extends Infoblocks {

    public function rules()
    {
        return [
            [['id', 'manager_id', 'created', 'modified', 'pages', 'status'], 'integer'],
            [['title'], 'string'],
        ];
    }

    public function search($params)
    {
        $query = Infoblocks::find()->managed()->byCurrentProject();

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
        $query->andFilterWhere(['like', 'title', $this->title]);
        if ($this->pages) {
            $query->andFilterWhere(['like', 'pages', '"page_id":"' . $this->pages . '"']);
        }

        return $dataProvider;
    }
}