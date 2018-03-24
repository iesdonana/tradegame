<?php

namespace app\controllers;

use app\models\TopValoraciones;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * TopValoracionesController implements the CRUD actions for TopValoraciones model.
 */
class TopValoracionesController extends Controller
{
    /**
     * Lists all TopValoraciones models.
     * @return mixed
     */
    public function actionTop()
    {
        $listado = TopValoraciones::find()->orderBy('avg DESC, totales DESC')->all();

        return $this->render('top', [
            'listado' => $listado,
        ]);
    }



    /**
     * Finds the TopValoraciones model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param string $id
     * @return TopValoraciones the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = TopValoraciones::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
