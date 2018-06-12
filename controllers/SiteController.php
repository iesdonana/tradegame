<?php

namespace app\controllers;

use app\models\LoginForm;
use app\models\Usuarios;
use Yii;
use app\models\VideojuegosUsuarios;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class SiteController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }


    /**
     * Cambia el idioma de la aplicación
     * @return string Idioma de la aplicación después del cambio
     */
    public function actionCambiarIdioma()
    {
        $lang = Yii::$app->request->post('lang');
        if ($lang !== null) {
            Yii::$app->language = $lang;
            $cookies = Yii::$app->response->cookies;

            $cookies->add(new \yii\web\Cookie([
                'name' => 'lang',
                'value' => $lang,
            ]));
        }
        return Yii::$app->language;
    }

    /**
     * Muestra la página de inicio del sitio web.
     * Esta página dependerá de si está logueado o no.
     *
     * @return string
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            $list = VideojuegosUsuarios::find()
                ->where(['visible' => true])
                ->andWhere(['borrado' => false])
                ->orderBy('created_at DESC')
                ->limit(5)
                ->all();
            return $this->render('index', [
                'lastVideojuegos' => $list,
            ]);
        }
        return $this->render('main');
    }

    /**
     * Realiza el login del usuario en el sitio web.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        $model->scenario = LoginForm::ESCENARIO_DEFAULT;
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $usuario = new Usuarios();
        $usuario->scenario = Usuarios::ESCENARIO_CREATE;
        $model->password = '';
        return $this->render('login', [
            'model' => $model,
            'modelRegistro' => $usuario,
        ]);
    }

    /**
     * Inicia sesión al usuario a través de Google
     * @return bool true si inicia sesión correctamente; false si no inicia sesión
     */
    public function actionLoginGoogle()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $email = Yii::$app->request->post('email');
        if ($email === null || ($usuario = Usuarios::find()
                ->where(['email' => $email])
                ->andWhere(['is', 'password', null])->one()) === null) {
            Yii::$app->session->setFlash('error', Yii::t('app', 'No se ha encontrado ningún usuario registrado con Google con ese email'));
            return false;
        }

        $model = new LoginForm();
        $model->username = $usuario->usuario;
        $model->rememberMe = false;
        if ($model->login()) {
            return true;
        }
        Yii::$app->response->format = Response::FORMAT_JSON;

        $usuario = new Usuarios();
        $usuario->scenario = Usuarios::ESCENARIO_CREATE;
        $model->password = '';
        return false;
    }

    /**
     * Realiza la acción de logout del usuario en el sitio web.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
