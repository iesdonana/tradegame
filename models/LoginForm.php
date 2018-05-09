<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * LoginForm es el modelo que recoge los datos del formulario de Login
 *
 * @property User|null $user This property is read-only.
 */
class LoginForm extends Model
{
    /**
     * Escenario para el login normal
     * @var string
     */
    const ESCENARIO_DEFAULT = 'custom_scenario';
    /**
     * Nombre de usuario
     * @var string
     */
    public $username;
    /**
     * Contraseña del usuario
     * @var string
     */
    public $password;
    /**
     * Recuerda al usuario
     * @var bool
     */
    public $rememberMe = true;
    /**
     * Usuario cacheado
     * @var string|bool
     */
    private $_user = false;


    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username'], 'required'],
            [['password'], 'required', 'on' => self::ESCENARIO_DEFAULT],
            ['rememberMe', 'boolean'],
            ['password', 'validatePassword', 'on' => self::ESCENARIO_DEFAULT],
        ];
    }

    /**
     * @return array Los nombres que se van a mostrar en pantalla
     */
    public function attributeLabels()
    {
        return [
            'username' => 'Usuario',
            'password' => 'Contraseña',
            'rememberMe' => 'Recuérdame',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || $user->password === null || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Nombre de usuario o contraseña incorrectos.');
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            $usuario = $this->getUser();
            if ($usuario->token_val !== null) {
                Yii::$app->session->setFlash(
                    'error',
                    'No has validado tu cuenta todavía. ' .
                    'Para iniciar sesión debes visitar el enlace enviado a su correo electrónico'
                );
                return false;
            }

            if ($usuario->ban > date('Y-m-d')) {
                Yii::$app->session->setFlash('error', 'Parece que has sido baneado. Vuelve a intentarlo ' .
                    Yii::$app->formatter->asRelativeTime($usuario->ban));
                return false;
            } elseif ($usuario->ban !== null) {
                $usuario->ban = null;
                $usuario->save();
            }

            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        }
        return false;
    }

    /**
     * Finds user by [[username]].
     *
     * @return User|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = Usuarios::findOne(['usuario' => $this->username]);
        }

        return $this->_user;
    }
}
