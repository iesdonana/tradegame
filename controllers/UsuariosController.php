<?php

namespace app\controllers;

use app\models\BanForm;
use app\models\Ofertas;
use app\models\Valoraciones;
use app\models\EmailResetForm;
use app\models\LoginForm;
use app\models\Usuarios;
use app\models\UsuariosId;
use HttpRequestException;
use Yii;
use app\models\VideojuegosUsuarios;

use app\helpers\Utiles;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * UsuariosController implements the CRUD actions for Usuarios model.
 */
class UsuariosController extends Controller
{
    public function behaviors()
    {
        return [
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'remove' => ['POST'],
                    'banear' => ['POST'],
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['registrar', 'modificar', 'banear'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['registrar'],
                        'roles' => ['?'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['modificar'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['banear'],
                        'matchCallback' => function ($rule, $action) {
                            if (!Yii::$app->user->isGuest && Yii::$app->user->identity->esAdmin()) {
                                return true;
                            }

                            return false;
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Crea un nuevo modelo de Usuarios y lo guarda en la base de datos.
     * Si se ha creado correctamente, se redireccionará a la pantalla de Login.
     * @throws HttpRequestException Si no se envía el correo de validación
     * @return mixed
     */
    public function actionRegistrar()
    {
        $model = new Usuarios();
        $model->scenario = Usuarios::ESCENARIO_CREATE;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $usuariosId = new UsuariosId();
            $usuariosId->save();

            $model->id = $usuariosId->id;
            $model->save(false);

            if ($this->enviarEmailValidacion($model)) {
                Yii::$app->session->setFlash(
                    'success',
                    'Se ha enviado un correo de confirmación a su correo electrónico. ' .
                    'Revíselo para poder iniciar sesión.'
                );
                return $this->redirect(['site/login']);
            }
            throw new HttpRequestException('No se ha podido completar la solicitud.');
        }

        $login = new LoginForm();
        $login->load(Yii::$app->request->post());
        return $this->render('/site/login', [
            'model' => $login,
            'modelRegistro' => $model,
        ]);
    }

    /**
     * Crea un modelo de Usuario a través de los datos recibidos por Google
     * @return bool true si se ha registrado correctamente; false si no se ha podido registrar
     */
    public function actionRegistrarGoogle()
    {
        $usuario = Yii::$app->request->post('usuario');
        $email = Yii::$app->request->post('email');
        $model = new Usuarios([
            'email' => $email
        ]);
        $model->usuario = Utiles::generarUsername($email);

        Yii::$app->response->format = Response::FORMAT_JSON;
        if ($model->validate()) {
            $usuariosId = new UsuariosId();
            $usuariosId->save();

            $model->id = $usuariosId->id;
            $model->save();

            Yii::$app->session->setFlash('success', 'Te has registrado correctamente con Google.');
            return true;
        }

        $errores = $model->errors;
        $msg = 'No se ha podido registrar con Google.';
        if (count($errores) > 0) {
            $msg = str_replace('"', '', reset($errores));
        }

        Yii::$app->session->setFlash('error', $msg);
        return false;
    }

    /**
     * Valida un usuario registrado.
     * @param  string $token_val Token de validación
     * @return mixed
     */
    public function actionValidar($token_val)
    {
        if (($user = Usuarios::findOne(['token_val' => $token_val])) === null) {
            return $this->goHome();
        }

        $user->token_val = null;
        $user->save(false);

        Yii::$app->session->setFlash('success', 'Has validado la cuenta correctamente.');
        $this->redirect(['site/login']);
    }

    /**
     * Manda un correo al email del usuario que se ha solicitado para recuperar
     * la contraseña.
     * @return mixed
     */
    public function actionRequestRecupera()
    {
        $email = Yii::$app->request->post('email');
        $emailResetForm = new EmailResetForm();
        if ($email !== null) {
            $emailResetForm->email = $email;

            if ($emailResetForm->validate()) {
                do {
                    $token = Yii::$app->security->generateRandomString();
                } while (Usuarios::findOne(['token_pass' => $token]) !== null);
                $user = Usuarios::findOne(['email' => $email]);
                $user->token_pass = $token;
                if ($user->save()) {
                    $emailResetForm->enviarCorreo($user);
                    Yii::$app->session->setFlash('success', 'Se te ha enviado un' .
                    ' correo electrónico con las instrucciones para recuperar la contraseña');
                    return $this->goHome();
                }
            }
        }

        return $this->render('email_recupera', [
            'model' => $emailResetForm,
        ]);
    }

    /**
     * Renderiza un formulario con los campos para introducir la nueva contraseña, y
     * cambia la contraseña del usuario.
     * @param  string $token_pass Token de password del usuario
     * @return mixed
     */
    public function actionRecuperar($token_pass)
    {
        if (($model = Usuarios::findOne(['token_pass' => $token_pass])) === null) {
            return $this->goHome();
        }

        $model->scenario = Usuarios::ESCENARIO_RECUPERACION;
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->token_pass = null;
            $model->save(false);
            Yii::$app->session->setFlash('success', 'Has cambiado tu contraseña ' .
                'correctamente. Ahora ya puedes usar tu nueva contraseña');
            $this->redirect(['site/login']);
        }
        $model->password = '';

        return $this->render('recupera', [
            'model' => $model,
        ]);
    }

    /**
     * Renderiza el perfil del usuario pasado por parámetro.
     * @param  string $usuario Nombre de usuario
     * @return mixed
     */
    public function actionPerfil($usuario)
    {
        if (($model = Usuarios::findOne(['usuario' => $usuario])) === null) {
            throw new NotFoundHttpException('El usuario no existe.');
        }

        return $this->render('profile', [
            'model' => $model,
            'listado' => $model->getUltimosVideojuegos(3),
        ]);
    }

    /**
     * Banea un usuario.
     * @return mixed
     */
    public function actionBanear()
    {
        $banForm = new BanForm();

        if ($banForm->load(Yii::$app->request->post()) && $banForm->validate()) {
            if (($usuario = Usuarios::findOne(['usuario' => $banForm->usuario])) === null) {
                throw new NotFoundHttpException('El usuario no existe.');
            }
            $usuario->ban = $banForm->fecha;
            if ($usuario->save()) {
                Yii::$app->session->setFlash('success', 'Has baneado al usuario correctamente.');
            }
        }

        return $this->redirect(['reportes/index']);
    }

    /**
     * Borra un usuario, así como datos relacionados en la base de datos.
     * Si se borra correctamente, nos mandará a la página de inicio de la aplicación.
     * @return mixed
     */
    public function actionRemove()
    {
        $user = Yii::$app->user->identity;
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($user->delete() !== false) {
                // Borra todas las ofertas que están pendientes de ser aceptadas o rechazadas
                $array = Ofertas::find()->joinWith('videojuegoOfrecido')
                    ->where(['videojuegos_usuarios.usuario_id' => $user->id])
                    ->andWhere(['is', 'aceptada', null])->all();
                foreach ($array as $oferta) {
                    $oferta->delete();
                }
                // Borra las valoraciones que están pendientes de realizarse, relacionadas con el usuario
                Valoraciones::deleteAll(
                    'usuario_valorado_id = ' . $user->id .
                    ' or (usuario_valora_id = ' . $user->id . ' and num_estrellas is null)'
                );
                $array = VideojuegosUsuarios::find()->where(['usuario_id' => $user->id])->all();
                foreach ($array as $vu) {
                    $vu->borrado = true;
                    $vu->save();
                }
            }
            $transaction->commit();
        } catch (\Exception $e) {
            $transaction->rollBack();
            throw $e;
        } catch (\Throwable $e) {
            $transaction->rollBack();
            throw $e;
        }

        if ($user->delete() !== false) {
            $array = Ofertas::find()->joinWith('videojuegoOfrecido')
                ->where(['videojuegos_usuarios.usuario_id' =>$user->id])
                ->andWhere(['is', 'aceptada', null])->all();
            // Borra todas las ofertas que están pendientes de ser aceptadas o rechazadas
            foreach ($array as $value) {
                $value->delete();
            }
        }
        Yii::$app->session->setFlash('success', 'Su cuenta se ha eliminado correctamente');
        return $this->goHome();
    }

    /**
     * Modifica los datos de un usuario
     * @param mixed $seccion Sección en la cuál está del apartado Modificación
     * @return mixed
     * @throws NotFoundHttpException Si la sección no es válida
     */
    public function actionModificar($seccion)
    {
        if (!in_array($seccion, ['datos', 'password', 'personal'])) {
            throw new NotFoundHttpException('No se ha encontrado lo que buscabas');
        }

        $model = Yii::$app->user->identity;
        $model->scenario = Usuarios::ESCENARIO_UPDATE;
        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->password === null) {
            $correo = $model->oldAttributes['email'];
        } else {
            $model->password = '';
        }

        if ($model->load(Yii::$app->request->post())) {
            $model->email = isset($correo) ? $correo : $model->email;
            $model->save();
            Yii::$app->session->setFlash('success', 'Has actualizado tus datos correctamente');
            if ($model->password !== null) {
                $model->password = $model->repeatPassword = $model->oldPassword = '';
            }
        }

        return $this->render('update', [
            'model' => $model,
            'seccion' => $seccion,
        ]);
    }

    /**
     * Envía un email al usuario que se ha registrado para validar su cuenta
     * a través de un enlace.
     * @param  Usuarios $model Usuario el cuál se quiere validar
     * @return bool         true si se ha enviado el correo correctamente.
     *                         false si ha fallado el envío del correo.
     */
    private function enviarEmailValidacion($model)
    {
        $url = Url::to([
            'usuarios/validar',
            'token_val' => $model->token_val,
        ], true);

        $content = 'Bienvenido a ' . Html::a('TradeGame', Url::home('http'), ['class' => 'url']) . '<br>' .
        'Para completar el registro en TradeGame debes validar tu cuenta, y
        así poder iniciar sesión en nuestro sitio web.
        Para validar tu cuenta haz click en el siguiente botón:<br><br><br>' .
        Html::a('Validar cuenta', $url, ['class' => 'boton']);

        return Yii::$app->mailer->compose('custom', [
                'usuario' => $model->usuario,
                'content' => $content,
            ])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($model->email)
            ->setSubject('Registro de cuenta en TradeGame')
            ->send();
    }

    /**
     * Busca usuarios a través de un texto pasado por parámetro
     * @param  string $q Cadena con la búsqueda
     * @return array     Resultados
     */
    public function actionBuscarUsuarios($q = null)
    {
        if (!Yii::$app->request->isAjax) {
            $this->goHome();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        $usuarios['results'] = [];
        if ($q !== null && $q !== '') {
            $usuarios['results'] = Usuarios::find()
                ->select(['id', 'usuario'])
                ->where(['ilike', 'usuario', $q])
                ->andWhere(['!=', 'id', Yii::$app->user->id])
                ->limit(10)
                ->orderBy('usuario')
                ->asArray()->all();
        }
        return $usuarios;
    }
}
