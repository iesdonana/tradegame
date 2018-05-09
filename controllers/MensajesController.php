<?php

namespace app\controllers;

use app\models\Mensajes;
use app\models\Usuarios;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

/**
 * MensajesController implements the CRUD actions for Mensajes model.
 */
class MensajesController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['listado', 'nuevo'],
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['listado'],
                        'roles' => ['@'],
                    ],
                    [
                        'allow' => true,
                        'actions' => ['nuevo'],
                        'matchCallback' => function ($rule, $action) {
                            if (!Yii::$app->user->isGuest &&
                            (Yii::$app->user->identity->usuario != Yii::$app->request->get('receptor'))) {
                                return true;
                            }

                            return false;
                        },
                    ],
                ],
            ],
        ];
    }

    /**
     * Renderiza la vista en la que se mostrará el listado de conversaciones, con sus
     * mensajes correspndientes
     * @param int $userSelect Id del usuario que vamos a seleccionar en la vista del listado
     * @return mixed
     */
    public function actionListado($userSelect = null)
    {
        $me = Yii::$app->user->id;
        $conversaciones = Mensajes::find()
            ->select('emisor_id')
            ->where(['receptor_id' => $me])
            ->distinct()
            ->all();
        $misConversaciones = Mensajes::find()
            ->select('receptor_id')
            ->where(['emisor_id' => $me])
            ->distinct()
            ->all();

        $res = $conversaciones;
        for ($i = 0; $i < count($misConversaciones); $i++) {
            $encontrado = false;
            for ($j = 0; $j < count($conversaciones); $j++) {
                if ($misConversaciones[$i]->receptor_id === $conversaciones[$j]->emisor_id) {
                    $encontrado = true;
                }
            }
            if (!$encontrado) {
                $res[] = $misConversaciones[$i];
            }
        }
        $params = [
            'conversaciones' => $res,
            'model' => new Mensajes(),
        ];

        if ($userSelect !== null) {
            $params = array_merge($params, ['userSelect' => $userSelect]);
        }
        return $this->render('listado', $params);
    }

    /**
     * Devuelve la vista de mensajes de una conversación con un usuario específico,
     * pasado por parámetro. Se buscara la conversación con el usuario logueado.
     * @param  string $id Id del usuario sobre el que queremos buscar la conversación
     * @return mixed
     */
    public function actionConversacion($id)
    {
        if (!Yii::$app->request->isAjax) {
            return $this->goHome();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;

        $me = Yii::$app->user->id;
        Yii::$app->db
            ->createCommand('UPDATE mensajes SET leido = true WHERE (emisor_id = :emisor AND receptor_id = :receptor)')
            ->bindValues([
                ':emisor' => $id,
                ':receptor' => $me,
            ])->execute();

        $lista = Mensajes::find()
            ->where(['and',
                ['emisor_id' => $id],
                ['receptor_id' => $me],
            ])
            ->orWhere([
                'and',
                ['emisor_id' => $me],
                ['receptor_id' => $id],
            ])->orderBy('created_at ASC')
            ->all();

        return $this->renderAjax('mensajes', [
            'lista' => $lista,
            'model' => new Mensajes(),
        ]);
    }

    /**
     * Crea un nuevo mensaje
     * @return mixed
     */
    public function actionCreate()
    {
        if (!Yii::$app->request->isAjax) {
            return $this->goHome();
        }

        Yii::$app->response->format = Response::FORMAT_JSON;
        $model = new Mensajes();
        $model->emisor_id = Yii::$app->user->id;
        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            return $model;
        }

        return $model;
    }

    /**
     * Crea un nuevo modelo de Mensajes, enviado por el usuario logueado.
     * @param  string $receptor Usuario al que se le va a enviar el mensaje
     * @return mixed
     */
    public function actionNuevo($receptor = null)
    {
        $model = new Mensajes();

        if ($receptor !== null) {
            if (($u = Usuarios::findOne(['usuario' => $receptor])) === null) {
                throw new NotFoundHttpException('No se ha encontrado el usuario');
            }
            $model->receptor_id = $u->id;
        }
        $model->emisor_id = Yii::$app->user->id;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Has enviado el mensaje correctamente');
            return $this->goHome();
        }

        return $this->render('nuevo', [
            'model' => $model,
        ]);
    }

    /**
     * Busca los mensajes que se han intercambiado
     * el usuario logueado y el usuario pasado por parámetro
     * @param  int $id Id del usuario con el que nos intercambiamos los mensajes
     * @return array          Array con los ID de los mensajes intercambiados entre los usuarios
     */
    public function actionMensajesNuevos($id)
    {
        Yii::$app->response->format = Response::FORMAT_JSON;

        $me = Yii::$app->user->id;
        return Mensajes::find()
            ->select('id')
            ->where(['and',
                ['emisor_id' => $id],
                ['receptor_id' => $me],
            ])
            ->orWhere([
                'and',
                ['emisor_id' => $me],
                ['receptor_id' => $id],
            ])->orderBy('id')
            ->column();
    }
}
