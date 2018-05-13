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
                'message' => Yii::t('app', 'No hay ningún usuario registrado con ese email.'),
            ],
        ];
    }

    /**
     * Envía un correo al usuario para poder resetear la contraseña por otra nueva
     * @param  string $usuario Usuario al cual le vamos a resetear la contraseña
     * @return bool            true si se ha mandado el correo correctamente; false si no se envía
     */
    public function enviarCorreo($usuario)
    {
        $content = Yii::t('app', 'Para resetear tu contraseña debes pulsar en el siguiente botón') . ':<br>' .
        Html::a(Yii::t('app', 'Recuperar contraseña'), Url::to(['usuarios/recuperar', 'token_pass' => $usuario->token_pass], true), ['class' => 'oferta']);
        return Yii::$app->mailer->compose('custom', [
                'usuario' => $usuario->usuario,
                'content' => $content
            ])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($usuario->email)
            ->setSubject(Yii::t('app', 'Recuperación de contraseña'))
            ->send();
    }
}
