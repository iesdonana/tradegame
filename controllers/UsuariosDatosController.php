<?php

namespace app\controllers;

use app\models\Usuarios;
use app\models\UsuariosDatos;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

/**
 * UsuariosDatosController implements the CRUD actions for UsuariosDatos model.
 */
class UsuariosDatosController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['modificar'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['modificar'],
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    /**
     * Updates an existing UsuariosDatos model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @return mixed
     * @throws NotFoundHttpException if the model cannot be found
     */
    public function actionModificar()
    {
        $model = Yii::$app->user->identity->usuariosDatos;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Has actualizado tus datos correctamente');
        }

        return $this->render('/usuarios/update', [
            'model' => $model,
            'seccion' => 'personal',
        ]);
    }

    /**
     * Finds the UsuariosDatos model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return UsuariosDatos the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = UsuariosDatos::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
