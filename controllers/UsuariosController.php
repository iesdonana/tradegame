<?php

namespace app\controllers;

use app\models\Usuarios;
use HttpRequestException;
use Yii;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\widgets\ActiveForm;

/**
 * UsuariosController implements the CRUD actions for Usuarios model.
 */
class UsuariosController extends Controller
{
    /**
     * Crea un nuevo modelo de Usuarios y lo guarda en la base de datos.
     * Si se ha creado correctamente, se redireccionará a la pantalla de Login.
     * @throws HttpRequestException Si no se envía el correo de validación
     * @return mixed
     */
    public function actionRegistrar()
    {
        $model = new Usuarios();

        if (Yii::$app->request->isAjax && $model->load(Yii::$app->request->post())) {
            Yii::$app->response->format = Response::FORMAT_JSON;
            return ActiveForm::validate($model);
        }

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            if ($this->enviarEmailValidacion($model)) {
                Yii::$app->session->setFlash(
                    'success',
                    'Se ha enviado un correo de confirmación a su correo electrónico. ' .
                    'Revíselo para poder iniciar sesión.'
                );
                return $this->redirect(['site/login']);
            }
            throw new HttpRequestException('No se ha podido completar la solicitud.');
        }

        return $this->render('create', [
            'model' => $model,
        ]);
    }

    /**
     * Valida un usuario registrado.
     * @param  string $token_val Token de validación
     * @return mixed
     */
    public function actionValidar($token_val)
    {
        if (($user = Usuarios::findOne(['token_val' => $token_val])) === null) {
            return $this->goHome();
        }

        $user->token_val = null;
        $user->save(false);

        Yii::$app->session->setFlash('success', 'Has validado la cuenta correctamente.');
        $this->redirect(['site/login']);
    }

    /**
     * Updates an existing Usuarios model.
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
     * Finds the Usuarios model based on its primary key value.
     * If the model is not found, a 404 HTTP exception will be thrown.
     * @param int $id
     * @return Usuarios the loaded model
     * @throws NotFoundHttpException if the model cannot be found
     */
    protected function findModel($id)
    {
        if (($model = Usuarios::findOne($id)) !== null) {
            return $model;
        }

        throw new NotFoundHttpException('La página solicitada no existe.');
    }

    /**
     * Envía un email al usuario que se ha registrado para validar su cuenta
     * a través de un enlace.
     * @param  Usuarios $model Usuario el cuál se quiere validar
     * @return bool         true si se ha enviado el correo correctamente.
     *                         false si ha fallado el envío del correo.
     */
    private function enviarEmailValidacion($model)
    {
        $url = Html::a('TradeGame', Url::to(['site/index'], true));
        return Yii::$app->mailer->compose()
                    ->setFrom(Yii::$app->params['adminEmail'])
                    ->setTo($model->email)
                    ->setSubject('Registro de cuenta en TradeGame')
                    ->setHtmlBody("Se ha registrado correctamente en $url. <br/>" .
                        'Para activar su cuenta debe hacer click ' .
                        Html::a(
                            'aquí',
                            Url::to([
                                'usuarios/validar',
                                'token_val' => $model->token_val,
                            ], true)
                        ))
                    ->send();
    }
}