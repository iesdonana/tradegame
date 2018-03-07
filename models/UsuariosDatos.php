<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "usuarios_datos".
 *
 * @property int $id_usuario
 * @property string $nombre_real
 * @property string $localidad
 * @property string $biografia
 * @property string $fecha_nacimiento
 *
 * @property Usuarios $usuario
 */
class UsuariosDatos extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'usuarios_datos';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_usuario'], 'required'],
            [['id_usuario'], 'default', 'value' => null],
            [['id_usuario'], 'integer'],
            [['fecha_nacimiento'], 'safe'],
            [['nombre_real', 'localidad', 'biografia'], 'string', 'max' => 255],
            [['id_usuario'], 'unique'],
            [['id_usuario'], 'exist', 'skipOnError' => true, 'targetClass' => Usuarios::className(), 'targetAttribute' => ['id_usuario' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_usuario' => 'Id Usuario',
            'nombre_real' => 'Nombre Real',
            'localidad' => 'Localidad',
            'biografia' => 'Biografia',
            'fecha_nacimiento' => 'Fecha Nacimiento',
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUsuario()
    {
        return $this->hasOne(Usuarios::className(), ['id' => 'id_usuario'])->inverseOf('usuariosDatos');
    }
}
