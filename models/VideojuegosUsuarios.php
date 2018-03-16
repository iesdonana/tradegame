<?php

namespace app\models;

/**
 * This is the model class for table "videojuegos_usuarios".
 *
 * @property int $id
 * @property int $videojuego_id
 * @property int $usuario_id
 * @property string $mensaje
 * @property string $created_at
 * @property bool $visible
 *
 * @property Usuarios $usuario
 * @property Videojuegos $videojuego
 */
class VideojuegosUsuarios extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'videojuegos_usuarios';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['videojuego_id', 'usuario_id'], 'required'],
            [['videojuego_id', 'usuario_id'], 'default', 'value' => null],
            [['visible'], 'default', 'value' => true],
            [['videojuego_id', 'usuario_id'], 'integer'],
            [['mensaje'], 'string', 'max' => 255],
            [['usuario_id'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['usuario_id' => 'id']],
            [['videojuego_id'], 'exist', 'skipOnError' => true, 'targetClass' => Videojuegos::className(), 'targetAttribute' => ['videojuego_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'videojuego_id' => 'Título del videojuego',
            'usuario_id' => 'Usuario ID',
            'mensaje' => 'Comentarios',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'usuario_id'])->inverseOf('videojuegosUsuarios');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getVideojuego()
    {
        return $this->hasOne(Videojuegos::className(), ['id' => 'videojuego_id'])->inverseOf('videojuegosUsuarios');
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfertasPublicados()
    {
        return $this->hasMany(Ofertas::className(), ['videojuego_publicado_id' => 'id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getOfertasOfrecidos()
    {
        return $this->hasMany(Ofertas::className(), ['videojuego_ofrecido_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public static function find()
    {
        return parent::find()->where(['visible' => true]);
    }
}
