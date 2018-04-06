<?php

namespace app\controllers;

use app\models\Usuarios;
use app\models\UsuariosId;
use HttpRequestException;
use Yii;
use app\models\EmailResetForm;

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
                ],
            ],
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['registrar', 'modificar'],
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

        return $this->render('create', [
            'model' => $model,
        ]);
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
     * la contraseña
     * @return mixed
     */
    public function actionRequestRecupera()
    {
        $email = Yii::$app->request->post('email');
        $emailResetForm = new EmailResetForm;
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
            'model' => $emailResetForm
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
            'model' => $model
        ]);
    }

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

    public function actionRemove()
    {
        $user = Yii::$app->user->identity;
        $user->delete();
        Yii::$app->session->setFlash('success', 'Su cuenta se ha eliminado correctamente');
        return $this->goHome();
    }

    /**
     * Modifica los datos de un usuario
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param mixed $seccion
     * @return mixed
     * @throws NotFoundHttpException Si el modelo no se puede encontrar
     */
    public function actionModificar($seccion)
    {
        $validos = ['datos', 'password', 'personal'];

        if (!in_array($seccion, $validos)) {
            throw new NotFoundHttpException('No se ha encontrado lo que buscabas');
        }

        $model = Yii::$app->user->identity;
        $model->scenario = Usuarios::ESCENARIO_UPDATE;

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        $model->password = '';
        if ($model->load(Yii::$app->request->post()) && $model->save(false)) {
            Yii::$app->session->setFlash('success', 'Has actualizado tus datos correctamente');
            $model->password = $model->repeatPassword = $model->oldPassword = '';
        }

        return $this->render('update', [
            'model' => $model,
            'seccion' => $seccion,
        ]);
    }

    /**
     * Finds the Usuarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Usuarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuarios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
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
