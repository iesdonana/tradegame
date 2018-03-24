<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;

/**
 * OfertasUsuariosSearch represents the model behind the search form of `app\models\OfertasUsuarios`.
 */
class OfertasUsuariosSearch extends OfertasUsuarios
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'videojuego_publicado_id', 'videojuego_ofrecido_id', 'id_publicado', 'id_ofrecido'], 'integer'],
            [['aceptada'], 'default'],
            [['created_at', 'publicado', 'ofrecido', 'usuario_publicado', 'usuario_ofrecido'], 'safe'],
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
     * @param mixed $estado
     * @param mixed $usuario
     *
     * @return ActiveDataProvider
     */
    public function search($params, $usuario, $estado)
    {
        $query = OfertasUsuarios::find()
            ->where(['usuario_publicado' => $usuario])
            ->where(['and',
                ['usuario_publicado' => $usuario],
                ['is', 'contraoferta_de', null],
            ])
            ->orWhere(['and',
                ['usuario_ofrecido' => $usuario],
                ['is not', 'contraoferta_de', null],
            ]);

        if ($estado === 'pendientes') {
            $query->andWhere(['is', 'aceptada', null]);
        } elseif ($estado === 'aceptadas') {
            $query->andWhere(['aceptada' => 1]);
        } elseif ($estado === 'rechazadas') {
            $query->andWhere(['aceptada' => 0]);
        }
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

        $query->andFilterWhere([
            'id' => $this->id,
            'videojuego_publicado_id' => $this->videojuego_publicado_id,
            'videojuego_ofrecido_id' => $this->videojuego_ofrecido_id,
            'contraoferta_de' => $this->contraoferta_de,
            'created_at' => $this->created_at,
        ]);

        if ($this->aceptada === null) {
            $query->andFilterWhere(['is', 'aceptada', null]);
        } else {
            $query->andFilterWhere(['aceptada' => $this->aceptada]);
        }

        $query->andFilterWhere(['ilike', 'publicado', $this->publicado])
            ->andFilterWhere(['ilike', 'ofrecido', $this->ofrecido])
            ->andFilterWhere(['ilike', 'usuario_publicado', $this->usuario_publicado])
            ->andFilterWhere(['ilike', 'usuario_ofrecido', $this->usuario_ofrecido]);

        return $dataProvider;
    }
}
