<?php

namespace app\controllers;

use Yii;
use Exception;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\Usuario;
use yii\filters\auth\HttpBearerAuth;

class UsuarioController extends  \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors['verbs'] = [
            'class' => VerbFilter::class,
            'actions' => [
                'iniciar-sesion' => ['post', 'get', 'options'],
                'buscar-usuario'  => ['get'],
            ]
        ];
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
            'except' => ['iniciar-sesion']
        ];
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['buscar-usuario', 'lista-usuarios', 'informacion-usuario'],
            'rules' => [
                [
                    'actions' => ['buscar-usuario', 'lista-usuarios'],
                    'allow' => true,
                    'roles' => ['Administrador'],
                ],
                [
                    'actions' => ['informacion-usuario'],
                    'allow' => true,
                    'roles' => ['Administrador', 'Docente'],
                ]
            ],
        ];

        return $behaviors;
    }

    public function beforeAction($action)
    {
        // Cambiamos el formato de respuesta de todas las acciones del controlador
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;

        // Deshabilita la validación CSRF, por defecto requerida en formularios
        $this->enableCsrfValidation = false;

        if (Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            Yii::$app->getResponse()->getHeaders()->set('Allow', 'POST GET PUT');
            Yii::$app->end();
        }
        return parent::beforeAction($action);
    }


    public function actionIniciarSesion()
    {
        /**
         * obtencion del valores enviados atravez del JSON
         */
        $params = Yii::$app->getRequest()->getBodyParams();
        try {
            $email = isset($params['email']) ? $params['email'] : null;
            $password = isset($params['password']) ? $params['password'] : null;
            $user = Usuario::findOne(['email' => $email]);

            /**
             * verificacion de la existencia del usuario
             */
            if (!$user) {
                /**
                 * Si no existe el usuario, se realiza una busqueda del uduario en el servidor de la websis
                 * mediante una peticion con el email del usuario
                 * Si el usuario es encontrado en la websis, se lo guarda en la base de datos del sistema
                 */
                $requestUser = $this->actionBuscarFuncionario($email);
                if (count($requestUser->result) > 0) {
                    $body = [
                        'nombre1' => ucwords(strtolower($requestUser->result[0]->NOMBRE1)),
                        'nombre2' => ucwords(strtolower($requestUser->result[0]->NOMBRE2)),
                        'paterno' => ucwords(strtolower($requestUser->result[0]->PATERNO)),
                        'materno' => ucwords(strtolower($requestUser->result[0]->MATERNO)),
                        'email' => $requestUser->result[0]->EMAIL,
                        'ci' => $requestUser->result[0]->CI,
                        'password' => Yii::$app->getSecurity()->generatePasswordHash($requestUser->result[0]->CI),
                        'token' =>  Yii::$app->security->generateRandomString()
                    ];

                    // Para crear un nuevo registro instanciamos el modelo
                    $model = new Usuario();

                    // load() esta cargando los parámetros del body al modelo
                    $model->load($body, '');

                    // el método save() devuelve un boolean
                    $model->save();

                    // Yii::$app->authManager usa la clase ManagerInterface
                    $auth = Yii::$app->authManager;

                    $role = $auth->getRole('Docente');
                    $auth->assign($role, $model->id_usuario); // Asigna el rol Administrador al usuario

                }
            }

            $user = Usuario::findOne(['email' => $email]);

            if ($user) {
                // Verificamos la contraseña
                if (Yii::$app->security->validatePassword($password, $user->password)) {
                    //Si las contraseñas coinciden directo devolvemos la respuesta
                    $response = [
                        'success' => true,
                        'id_usuario' =>  $user->id_usuario,
                        'token' => $user->token
                    ];
                    return $response;
                }
            }

            // Si no se encuentra el usuario u no coinciden las contraseñas envia error
            Yii::$app->getResponse()->setStatusCode(400);
            $response = [
                'success' => false,
                'message' => 'Usuario y/o Contraseña incorrecto.'
            ];
        } catch (Exception $e) {
            Yii::$app->getResponse()->setStatusCode(500);
            $response = [
                'success' => false,
                'message' => $e->getMessage(),
                'code' => $e->getCode(),
            ];
        }
        return $response;
    }

    /** Consulta en SW de Repositorio si un usuario es funcionario UMSS */
    public function actionBuscarFuncionario($email)
    {

        $body = array(
            'email' => $email,
        );


        $token = $this->getToken();
        if (!is_array($token) && count(explode(".", ($token))) == 3) {
            $crl = curl_init(\Yii::$app->params['apiServer'] . '/apicurtemp/buscar-funcionario');
            $post_data = json_encode($body);
            curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($crl, CURLINFO_HEADER_OUT, true);
            curl_setopt($crl, CURLOPT_POST, true);
            curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
            curl_setopt($crl, CURLOPT_HTTPHEADER, array(
                'Authorization: Bearer ' . $token,
                'Content-Type: application/json',
            ));
            $res = curl_exec($crl);
            curl_close($crl);
            if (!$res) {
                die("Problema de comunicacion WS externo");
            }

            return json_decode($res);
        } else {
            return ['error' => 'Acceso no autorizado'];
        }
    }

    /**
     * funcion que permite obtener el token de asocido con la sesion iniciada en el sistema websis
     * retorna el token
     */
    private function getToken()
    {
        $params = Yii::$app->params;
        $pathToken = $params['tkns'];


        $token = "";


        if (file_exists($pathToken)) {
            $token = file_get_contents($pathToken);
            $tokenParts = explode(".", $token);
            if (count($tokenParts) === 3) {
                $payload = base64_decode($tokenParts[1]);
                $valid = (json_decode($payload)->exp);
                if (time() > $valid - 20) {
                    $token = $this->login();
                }
            } else {
                $token = $this->login();
            }
        } else {
            $token = $this->login();
            $fp = fopen($pathToken, 'w+');
            fwrite($fp, $token);
            fclose($fp);
        }
        return $token;
    }

    /**
     * funcion que permite iniciar sesion el sistema Websis
     */
    private function login()
    {
        $params = Yii::$app->params;
        $credentials = ['username' => $params['apiUsr'], 'pwd' => $params['apiPwd']];
        $url = $params['apiServer'] . '/api/login';


        $crl = curl_init($url);
        $post_data = json_encode($credentials);
        curl_setopt($crl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($crl, CURLINFO_HEADER_OUT, true);
        curl_setopt($crl, CURLOPT_POST, true);
        curl_setopt($crl, CURLOPT_POSTFIELDS, $post_data);
        curl_setopt($crl, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        $res = curl_exec($crl);


        if (!$res) {
            die("Problema de comunicacion WS externo");
        }
        curl_close($crl);


        $res = json_decode($res);
        $token = $res->result->token;


        return $token;
    }

    public function actionBuscarUsuario($ci)
    {
        $usuario = Usuario::find()
            ->where(['ci' => $ci])->one();

        if ($usuario) {
            return [
                'success' => true,
                'message' => 'usuario encontrado correctamente',
                'data' => $usuario
            ];
        } else {
            return [
                'success' => false,
                'message' => 'No se pudo encontrar el usuario',
            ];
        }
    }

    public function actionObtenerListaUsuario($ci)
    {
        $usuarios = Usuario::find()->select(['id_usuario', 'ci'])
            ->where(['like', 'ci', $ci])->all();
        return [
            'success' => true,
            'message' => 'accion realizada correctamente',
            'data' => $usuarios
        ];
    }

    public function actionInformacionUsuario($idUsuario)
    {
        $usuario = Usuario::find()
            ->select(['nombre1', 'nombre2', 'paterno', 'materno', 'email', 'ci'])
            ->where(['id_usuario' => $idUsuario])->one();

        if ($usuario) {
            return [
                'success' => true,
                'message' => 'usuario encontrado correctamente',
                'data' => $usuario
            ];
        } else {
            return [
                'success' => false,
                'message' => 'No se pudo encontrar el usuario',
            ];
        }
    }
}
