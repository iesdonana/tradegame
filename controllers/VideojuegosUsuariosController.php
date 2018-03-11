<?php

namespace app\controllers;

use app\models\VideojuegosUsuarios;
use yii\web\Controller;

/**
 * VideojuegosUsuariosController implements the CRUD actions for VideojuegosUsuarios model.
 */
class VideojuegosUsuariosController extends Controller
{
    public function actionPublicar()
    {
        $model = new VideojuegosUsuarios();

        return $this->render('publicar', [
            'model' => $model,
        ]);
    }
}
