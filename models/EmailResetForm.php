<?php

namespace app\models;

use Yii;
use yii\base\Model;

use yii\helpers\Url;
use yii\helpers\Html;

class EmailResetForm extends Model
{
    /**
     * Email del usuario al que vamos a resetear la contraseña
     * @var string
     */
    public $email;

    public function formName()
    {
        return '';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'required'],
            ['email', 'trim'],
            ['email', 'email'],
            ['email', 'exist',
                'targetClass' => Usuarios::className(),
                'message' => 'No hay ningún usuario registrado con ese email.',
            ],
        ];
    }

    public function enviarCorreo($usuario)
    {
        $content = 'Para resetear tu contraseña debes pulsar en el siguiente botón:<br>' .
        Html::a('Recuperar contraseña', Url::to(['usuarios/recuperar', 'token_pass' => $usuario->token_pass], true), ['class' => 'oferta']);
        return Yii::$app->mailer->compose('custom', [
                'usuario' => $usuario->usuario,
                'content' => $content
            ])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($usuario->email)
            ->setSubject('Recuperación de contraseña')
            ->send();
    }
}
