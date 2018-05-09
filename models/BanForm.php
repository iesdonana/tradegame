<?php

namespace app\models;

use yii\base\Model;

/**
 * Modelo que recoge los datos del formulario de baneo de usuarios
 */
class BanForm extends Model
{
    /**
     * Fecha hasta la cuál el usuario va a estar baneado
     * @var string
     */
    public $fecha;

    /**
     * Usuario al cuál se va a banear
     * @var string
     */
    public $usuario;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['fecha', 'required'],
            [['usuario'], 'safe'],
        ];
    }
}
