<?php

namespace app\controllers;

use app\models\Ofertas;
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
                            if (($vUsuario = VideojuegosUsuarios::findOne($publicacion)) === null) {
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
     * Updates an existing Ofertas model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionUpdate($id)
    {
        $model = $this->findModel($id);

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $this->redirect(['view', 'id' => $model->id]);
        }

        return $this->render('update', [
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
            if ($model->aceptada) {
                $publicado = $model->videojuegoPublicado;
                $ofrecido = $model->videojuegoOfrecido;
                $publicado->visible = $ofrecido->visible = false;
                $publicado->save();
                $ofrecido->save();
            }

            Yii::$app->session->setFlash('success', 'Se ha cambiado el estado correctamente');
        }
        return $this->redirect('/ofertas-usuarios/index');
    }

    /**
     * Deletes an existing Ofertas model.
     * If deletion is successful, the browser will be redirected to the 'index' page.
     * @param int $id
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionDelete($id)
    {
        $this->findModel($id)->delete();

        return $this->redirect(['index']);
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
