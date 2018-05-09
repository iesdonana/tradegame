<?php

namespace app\models;

use Yii;
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
            [['id'], 'integer'],
            [['comentario'], 'safe'],
            [['num_estrellas'], 'number'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied.
     *
     * @param array $params
     * @param mixed $estado
     *
     * @return ActiveDataProvider
     */
    public function search($params, $estado)
    {
        $query = Valoraciones::find()->where(['usuario_valora_id' => Yii::$app->user->id]);

        // add conditions that should always apply here
        if ($estado === 'pendientes') {
            $query->andWhere(['is', 'num_estrellas', null]);
        } elseif ($estado === 'valoradas') {
            $query->andWhere(['is not', 'num_estrellas', null]);
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            return $dataProvider;
        }

        $query->andFilterWhere([
            'num_estrellas' => $this->num_estrellas,
        ]);

        $query->andFilterWhere(['ilike', 'comentario', $this->comentario]);

        return $dataProvider;
    }
}
