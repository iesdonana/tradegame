<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * ValoracionesSearch represents the model behind the search form of `app\models\Valoraciones`.
 */
class ValoracionesSearch extends Valoraciones
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'oferta_id'], 'integer'],
            [['comentario'], 'safe'],
            [['num_estrellas'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = Valoraciones::find()->with(['oferta']);

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'oferta_id' => $this->oferta_id,
            'num_estrellas' => $this->num_estrellas,
        ]);

        $query->andFilterWhere(['ilike', 'comentario', $this->comentario]);

        return $dataProvider;
    }
}
