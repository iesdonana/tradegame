<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "valoraciones".
 *
 * @property int $id
 * @property int $oferta_id
 * @property string $comentario
 * @property string $num_estrellas
 * @property bool $pendiente
 *
 * @property Ofertas $oferta
 */
class Valoraciones extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'valoraciones';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['oferta_id'], 'required'],
            [['oferta_id'], 'default', 'value' => null],
            [['oferta_id'], 'integer'],
            [['num_estrellas'], 'number'],
            [['pendiente'], 'boolean'],
            [['comentario'], 'string', 'max' => 255],
            [['oferta_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ofertas::className(), 'targetAttribute' => ['oferta_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'oferta_id' => 'Oferta ID',
            'comentario' => 'Comentario',
            'num_estrellas' => 'Num Estrellas',
            'pendiente' => 'Pendiente',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOferta()
    {
        return $this->hasOne(Ofertas::className(), ['id' => 'oferta_id'])->inverseOf('valoraciones');
    }
}
