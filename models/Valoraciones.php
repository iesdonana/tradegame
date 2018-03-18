<?php

namespace app\models;

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
     * Escenario para la creación de una Valoración.
     * @var string
     */
    const ESCENARIO_CREATE = 'create';

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
            [['num_estrellas'], 'required', 'on' => self::ESCENARIO_CREATE],
            [['oferta_id'], 'default', 'value' => null],
            [['oferta_id'], 'integer'],
            [['num_estrellas'], 'number'],
            [['comentario'], 'string', 'max' => 255],
            [['oferta_id'], 'unique', 'message' => 'Esa valoración ya ha sido creada'],
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
            'num_estrellas' => 'Valoración',
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
