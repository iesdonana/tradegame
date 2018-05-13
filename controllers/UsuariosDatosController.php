<?php

namespace app\controllers;

use app\models\UsuariosDatos;
use app\models\UsuariosGeneros;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\UploadedFile;

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
     * Modifica los datos del usuario logueado.
     * @return mixed
     */
    public function actionModificar()
    {
        $model =  Yii::$app->user->identity->usuariosDatos;

        if ($model->load(Yii::$app->request->post())) {
            $model->foto = UploadedFile::getInstance($model, 'foto');
            if ($model->localidad === null && $model->direccion === null) {
                $model->geoloc = null;
            }
            if ($model->save() && $model->upload()) {
                Yii::$app->session->setFlash('success', Yii::t('app', 'Has actualizado tus datos correctamente'));
            }
        }

        return $this->render('/usuarios/update', [
            'modelDatos' => $model,
            'seccion' => 'personal',
            'generos' => UsuariosGeneros::find()
                ->select('sexo')
                ->indexBy('id')
                ->column(),
        ]);
    }
}
