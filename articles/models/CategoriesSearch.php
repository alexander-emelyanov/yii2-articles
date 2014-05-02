<?php

namespace app\modules\articles\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\modules\articles\models\Categories;

/**
 * CategoriesSearch represents the model behind the search form about `app\modules\articles\models\Categories`.
 */
class CategoriesSearch extends Categories
{
    public function rules()
    {
        return [
            [['id', 'parent', 'published', 'access', 'ordering'], 'integer'],
            [['name', 'alias', 'description', 'image', 'image_caption', 'image_credits', 'params', 'metadesc', 'metakey', 'robots', 'author', 'copyright', 'language'], 'safe'],
        ];
    }

    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    public function search($params)
    {
        $query = Categories::find();

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        if (!($this->load($params) && $this->validate())) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'id' => $this->id,
            'parent' => $this->parent,
            'published' => $this->published,
            'access' => $this->access,
            'ordering' => $this->ordering,
        ]);

        $query->andFilterWhere(['like', 'name', $this->name])
            ->andFilterWhere(['like', 'alias', $this->alias])
            ->andFilterWhere(['like', 'description', $this->description])
            ->andFilterWhere(['like', 'image', $this->image])
            ->andFilterWhere(['like', 'image_caption', $this->image_caption])
            ->andFilterWhere(['like', 'image_credits', $this->image_credits])
            ->andFilterWhere(['like', 'params', $this->params])
            ->andFilterWhere(['like', 'metadesc', $this->metadesc])
            ->andFilterWhere(['like', 'metakey', $this->metakey])
            ->andFilterWhere(['like', 'robots', $this->robots])
            ->andFilterWhere(['like', 'author', $this->author])
            ->andFilterWhere(['like', 'copyright', $this->copyright])
            ->andFilterWhere(['like', 'language', $this->language]);

        return $dataProvider;
    }
}