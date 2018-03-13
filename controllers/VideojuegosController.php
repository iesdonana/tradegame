<?php

namespace app\controllers;

use app\models\Videojuegos;
use Yii;
use yii\web\Controller;
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
        Yii::$app->response->format = Response::FORMAT_JSON;
        $videojuegos['results'] = [];
        if ($q !== null && $q !== '') {
            $videojuegos['results'] = Videojuegos::find()
                ->joinWith('plataforma')
                ->where(['ilike', 'videojuegos.nombre', $q])
                ->limit(10)->select([
                    'videojuegos.id', 'videojuegos.nombre',
                    'plataformas.nombre as plataforma', 'plataforma_id', ])
                ->asArray()->all();
        }
        return $videojuegos;
    }
}
