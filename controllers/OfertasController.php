<?php

namespace app\controllers;

use app\models\Ofertas;
use app\models\Valoraciones;
use app\models\VideojuegosUsuarios;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

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
                'only' => ['create'],
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
                                ->andWhere(['visible' => true])->one()) === null) {
                                return false;
                            }

                            return $vUsuario->usuario_id !== Yii::$app->user->id;
                        },
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

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
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
                // a estar visible
                $ofertasPublicados = $publicado->getOfertasPublicados()
                    ->where(['is', 'aceptada', null])->all();
                foreach ($ofertasPublicados as $oferta) {
                    $oferta->aceptada = false;
                    $oferta->save();
                }
                $publicado->save();
                $ofrecido->save();

                $valoracion = new Valoraciones([
                    'usuario_valora_id' => Yii::$app->user->id,
                    'usuario_valorado_id' => $model->videojuegoOfrecido->usuario_id,
                ]);
                $valoracion->save();
                return $this->redirect(['valoraciones/valorar', 'id' => $valoracion->id]);
            }
        }
        return $this->redirect('/ofertas-usuarios/index');
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
