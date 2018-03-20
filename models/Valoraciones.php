<?php

namespace app\models;

/**
 * This is the model class for table "valoraciones".
 *
 * @property int $id
 * @property string $comentario
 * @property string $usuario_valora
 * @property string $usuario_valorar
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
            [['num_estrellas'], 'required', 'on' => self::ESCENARIO_CREATE],
            [['usuario_valorado_id', 'usuario_valora_id'], 'integer'],
            [['num_estrellas'], 'number'],
            [['comentario'], 'string', 'max' => 255],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'usuario_valorado_id' => 'Usuario a valorar',
            'comentario' => 'Comentario',
            'num_estrellas' => 'Valoración',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioValorado()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_valorado_id'])->inverseOf('valoraciones');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuarioValora()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_valora_id'])->inverseOf('valoraciones0');
    }
}
