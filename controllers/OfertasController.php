<?php

namespace app\controllers;

use app\models\Ofertas;
use app\models\Usuarios;
use app\models\Valoraciones;
use app\models\VideojuegosUsuarios;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

use yii\helpers\Url;
use yii\helpers\Html;

/**
 * OfertasController implements the CRUD actions for Ofertas model.
 */
class OfertasController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['create', 'contraoferta'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['create'],
                        'matchCallback' => function ($rule, $action) {
                            if (Yii::$app->user->isGuest) {
                                return false;
                            }

                            $publicacion = Yii::$app->request->get('publicacion');
                            if (($vUsuario = VideojuegosUsuarios::find()
                                ->where(['id' => $publicacion])
                                ->andWhere(['borrado' => false])
                                ->andWhere(['visible' => true])->one()) === null) {
                                return false;
                            }

                            return $vUsuario->usuario_id !== Yii::$app->user->id;
                        },
                    ],
                    [
                        'allow' => true,
                        'actions' => ['contraoferta'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Creates a new Ofertas model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     * @param mixed $publicacion Id del videojuego del usuario al que le vamos a
     *                           hacer la oferta
     * @return mixed
     */
    public function actionCreate($publicacion)
    {
        $model = new Ofertas();
        $model->scenario = Ofertas::ESCENARIO_CREATE;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            $vjPublicado = $model->videojuegoPublicado;
            $this->enviarEmailOferta($vjPublicado->usuario, $vjPublicado->videojuego->nombre);
            Yii::$app->session->setFlash('success', 'Has realizado la oferta correctamente');
            return $this->goHome();
        }

        $model->videojuego_publicado_id = $publicacion;
        $model->videojuego_ofrecido_id = null; // No se muestra nuevamente el videojuego ofrecido
        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Realiza una contraoferta sobre una oferta ya recibida.
     * @param  int   $oferta Id de la oferta sobre la cual vamos a hacer uan
     *                       contraoferta
     * @return mixed
     */
    public function actionContraoferta($oferta)
    {
        $model = new Ofertas();

        if (($modelOferta = Ofertas::findOne($oferta)) === null) {
            throw new NotFoundHttpException('No existe la oferta');
        }

        if (Ofertas::findOne(['contraoferta_de' => $modelOferta->id]) !== null) {
            throw new NotFoundHttpException('Ya se ha contraofertado');
        }

        $model->contraoferta_de = $modelOferta->id;
        $model->scenario = Ofertas::ESCENARIO_CREATE;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            // Rechazamos la oferta anterior, al hacerle una contraoferta
            $ofertaPrincipal = $model->contraofertaDe;
            $ofertaPrincipal->aceptada = false;
            $ofertaPrincipal->save();
            $vjOfrecido = $model->videojuegoOfrecido;
            $this->enviarEmailOferta($vjOfrecido->usuario, $model->videojuegoPublicado->videojuego->nombre, true);
            Yii::$app->session->setFlash('success', 'Has realizado la contraoferta correctamente');
            return $this->goHome();
        }

        $model->videojuego_publicado_id = $modelOferta->videojuego_publicado_id;
        $model->videojuego_ofrecido_id = null; // No se muestra nuevamente el videojuego ofrecido
        return $this->render('create', [
            'model' => $model,
            'usuarioOfrecido' => $modelOferta->videojuegoOfrecido->usuario,
        ]);
    }


    /**
     * Acepta o Rechaza una oferta.
     * @return mixed
     */
    public function actionCambiarEstado()
    {
        if (($valor = Yii::$app->request->post('valor')) === null) {
            throw new NotFoundHttpException('No se encontró el valor');
        }

        if (($id = Yii::$app->request->post('id')) === null) {
            throw new NotFoundHttpException('No se encontró la oferta');
        }

        if (($model = $this->findModel($id)) === null) {
            throw new NotFoundHttpException('No se encontró la oferta');
        }

        $validos = [0, 1];
        if (!in_array($valor, $validos)) {
            throw new ForbiddenHttpException('No es posible ejecutar esa acción');
        }

        $model->aceptada = $valor;
        if ($model->save()) {
            Yii::$app->session->setFlash('success', 'Se ha cambiado el estado correctamente');
            if ($model->aceptada) {
                $publicado = $model->videojuegoPublicado;
                $ofrecido = $model->videojuegoOfrecido;
                $publicado->visible = $ofrecido->visible = false;
                // Se rechazan automáticamente el resto de ofertas por este juego
                // por el que acabamos de aceptar una oferta, ya que no volverá
                // a estar visible. Además se borran las ofertas pendientes en
                // las que el videojuego ofrecido sea el que hemos aceptado que
                // nos ofrezcan
                $ofertasOfrecido = $ofrecido->getOfertasOfrecidos()
                    ->where(['is', 'aceptada', null])->all();
                foreach ($ofertasOfrecido as $oferta) {
                    $oferta->delete();
                }
                $ofertasPublicados = $publicado->getOfertasPublicados()
                    ->where(['is', 'aceptada', null])->all();
                foreach ($ofertasPublicados as $oferta) {
                    $oferta->aceptada = false;
                    $oferta->save();
                }
                $publicado->save();
                $ofrecido->save();

                $usuarioValora = $publicado->usuario->id;
                $usuarioValorado = $ofrecido->usuario->id;

                $valoracion = new Valoraciones([
                    'usuario_valora_id' => $usuarioValora,
                    'usuario_valorado_id' => $usuarioValorado,
                ]);
                $valoracion->save();
                $valoracion = new Valoraciones([
                    'usuario_valora_id' => $usuarioValorado,
                    'usuario_valorado_id' => $usuarioValora
                ]);
                $valoracion->save();
                return $this->redirect(['valoraciones/valorar', 'id' => $valoracion->id]);
            }
        }
        return $this->redirect('/ofertas-usuarios/index');
    }

    /**
     * Envia un email informativo al usuario pasado por parámetro, para informarle
     * de una nueva oferta/contraoferta
     * @param  Usuarios  $usuario      Modelo de Usuarios al cuál le vamos a enviar el correo
     * @param  string    $videojuego   Nombre del videojuego sobre el cuál se hace la oferta
     * @param  bool      $contraoferta True si es una contraoferta, false si no lo es
     * @return bool                    Si se ha completado el envío del correo retornará
     *                                 true, si no retornará false.
     */
    private function enviarEmailOferta($usuario, $videojuego, $contraoferta = false)
    {
        $content = 'Parece que has recibido una oferta de alguien por tu ' . $videojuego . '<br>' .
            'Para ver la oferta pulsa en el siguiente botón:<br>' .
            Html::a('Ver mis ofertas', Url::to('/ofertas', true), ['class' => 'oferta']);
        if ($contraoferta) {
            $content = 'Parece que has recibido una contraoferta de alguien por su ' . $videojuego . '<br>' .
            'Para ver la contraoferta pulsa en el siguiente botón:<br>' .
            Html::a('Ver mis ofertas', Url::to('/ofertas', true), ['class' => 'oferta']);
        }

        return Yii::$app->mailer->compose('custom', [
                'usuario' => $usuario->usuario,
                'content' => $content
            ])
            ->setFrom(Yii::$app->params['adminEmail'])
            ->setTo($usuario->email)
            ->setSubject('¡Has recibido una oferta!')
            ->send();
    }

    /**
     * Finds the Ofertas model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Ofertas the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Ofertas::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
