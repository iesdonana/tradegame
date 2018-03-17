<?php

namespace app\controllers;

use app\models\Videojuegos;
use app\models\VideojuegosUsuarios;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * VideojuegosUsuariosController implements the CRUD actions for VideojuegosUsuarios model.
 */
class VideojuegosController extends Controller
{
    /**
     * Hace una búsqueda de videojuegos por el nombre.
     * @param  string $q Búsqueda
     * @return string    Respuesta en JSON
     */
    public function actionBuscarVideojuegos($q = null)
    {
        if (!Yii::$app->request->isAjax) {
            $this->goHome();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        $videojuegos['results'] = [];
        if ($q !== null && $q !== '') {
            $videojuegos['results'] = Videojuegos::find()
                ->select(['videojuegos.id', 'videojuegos.nombre',
                'plataformas.nombre as plataforma', 'plataforma_id', ])
                ->joinWith('plataforma')
                ->where(['ilike', 'videojuegos.nombre', $q])
                ->limit(10)
                ->orderBy('videojuegos.nombre')
                ->asArray()->all();
        }
        return $videojuegos;
    }

    /**
     * Busca un videojuego a través del input del NavBar.
     * @param null|mixed $q Búsqueda
     * @return string       Respuesta en JSON
     */
    public function actionBuscadorVideojuegos($q = '')
    {
        $res = [];

        $videojuegos = Videojuegos::find()
            ->with('plataforma')
            ->where(['ilike', 'videojuegos.nombre', $q])
            ->orderBy('videojuegos.nombre')->limit(10);

        if (Yii::$app->request->isAjax) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            $videojuegos = $videojuegos->all();
            foreach ($videojuegos as $videojuego) {
                $res[] = [
                    'id' => $videojuego->id,
                    'nombre' => $videojuego->nombre,
                    'plataforma' => $videojuego->plataforma->nombre,
                ];
            }
        } else {
            $dataProvider = new ActiveDataProvider([
                'query' => $videojuegos,
                'pagination' => [
                    'pageSize' => 5,
                ],
            ]);

            $res = $this->render('busqueda', [
                'dataProvider' => $dataProvider,
            ]);
        }

        return $res;
    }

    /**
     * Busca un videojuego y devuelve su carátula y su título, para poder
     * ver una previsualización del mismo a la hora de hacer una oferta.
     * @param  int   $id Id del videojuego
     * @return mixed
     */
    public function actionOfertaVideojuego($id)
    {
        if (($videojuegoUsuario = VideojuegosUsuarios::findOne($id)) === null) {
            throw new NotFoundHttpException('No se encontró el videojuego');
        }
        Yii::$app->response->format = Response::FORMAT_JSON;
        $videojuego = $videojuegoUsuario->videojuego;
        $datos = [
            'titulo' => $videojuego->nombre,
            'caratula' => $videojuego->caratula,
        ];

        return $datos;
    }

    /**
     * Devuelve una vista con los detalles de un videojuego concreto.
     * @param  int   $id Id del videojuego
     * @return mixed
     */
    public function actionDetalles($id)
    {
        if (($videojuego = Videojuegos::findOne($id)) === null) {
            throw new NotFoundHttpException('No se encontró el videojuego');
        }

        return $this->renderAjax('detalles', [
            'videojuego' => $videojuego,
        ]);
    }

    public function actionVer($id)
    {
        if (($videojuego = Videojuegos::findOne($id)) === null) {
            throw new NotFoundHttpException('No se encontró el videojuego');
        }

        $dataProvider = new ActiveDataProvider([
            'query' => $videojuego->getVideojuegosUsuarios()->orderBy('created_at DESC'),
        ]);

        return $this->render('view', [
            'model' => $videojuego,
            'dataProvider' => $dataProvider,
        ]);
    }
}
