<?php

namespace app\controllers;

use app\models\Usuarios;
use app\models\VideojuegosUsuarios;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * VideojuegosUsuariosController implements the CRUD actions for VideojuegosUsuarios model.
 */
class VideojuegosUsuariosController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['publicar'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['publicar'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Publica un videojuego para ponerlo visible para los demás usuarios y que
     * así nos puedan hacer ofertas por él.
     * @return mixed
     */
    public function actionPublicar()
    {
        $model = new VideojuegosUsuarios();
        $model->usuario_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Has publicado el videojuego correctamente');
            return $this->goHome();
        }

        return $this->render('publicar', [
            'model' => $model,
        ]);
    }

    /**
     * Muestra las publicaciones de un usuario pasado por parámetro.
     * @param  string $usuario Nombre de usuario
     * @return mixed
     * @throws NotFoundHttpException Si no se ha encontrado el usuario
     */
    public function actionPublicaciones($usuario)
    {
        if (($model = Usuarios::findOne(['usuario' => $usuario])) === null) {
            throw new NotFoundHttpException("No se ha encontrado el usuario '$usuario'");
        }

        $dataProvider = new ActiveDataProvider([
            'query' => VideojuegosUsuarios::find()
                ->with('videojuego')
                ->where(['usuario_id' => $model->id]),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('publicaciones', [
            'model' => $model,
            'dataProvider' => $dataProvider,
        ]);
    }
}
