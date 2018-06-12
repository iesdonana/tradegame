<?php

namespace app\controllers;

use app\models\TopValoraciones;
use yii\web\Controller;

use yii\data\Pagination;

/**
 * TopValoracionesController implements the CRUD actions for TopValoraciones model.
 */
class TopValoracionesController extends Controller
{
    /**
     * Muestra un listado con el top de valoraciones de todo el sitio web.
     * @return mixed
     */
    public function actionTop()
    {
        $listado = TopValoraciones::find()
            ->orderBy('avg DESC, totales DESC');
        $pages = new Pagination([
            'totalCount' => $listado->count(),
            'pageSize' => 10,
        ]);

        $models = $listado->offset($pages->offset)
        ->limit($pages->limit)
        ->all();

        return $this->render('top', [
            'listado' => $models,
            'pages' => $pages
        ]);
    }
}
