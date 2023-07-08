<?php

namespace app\controllers;

use app\models\Categoria;
use app\models\DeclaracionJurada;
use app\models\DeclaracionJuradaCategoria;
use app\models\DeclaracionJuradaInstitucion;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use \yii\filters\auth\HttpBearerAuth;
use app\models\InformacionFechas;
use app\models\Institucion;
use app\models\Usuario;
use app\models\UsuarioCategoria;
use app\models\UsuarioInstitucion;

class DeclaracionJuradaController extends  \yii\web\Controller
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
                'crear-declaracion-jurada' => ['post', 'options'],
                'editar-declaracion' => ['post', 'options'],
                'actualizar-declaracion' => ['post', 'options'],
                'eliminar-declaracion' => ['post', 'options'],
                'obtener-gestiones' => ['get'],
                'obtener-declaraciones-gestion' => ['get'],
                'obtener-declaracion' => ['get'],
                'obtener-declaracion-jurada' => ['get'],
            ]
        ];
        $behaviors['authenticator'] = [
            'class' => HttpBearerAuth::class,
        ];
        $behaviors['access'] = [
            'class' => AccessControl::class,
            'only' => ['crear-declaracion-jurada', 'editar-declaracion', 'actualizar-declaracion',
                        'eliminar-declaracion', 'obtener-gestiones', 'obtener-declaraciones-gestion',
                        'obtener-declaracion', 'obtener-declaracion-jurada'],
                'rules' => [
                    [
                        'actions' => ['crear-declaracion-jurada', 'editar-declaracion', 'actualizar-declaracion',
                                    'eliminar-declaracion'],
                        'allow' => true,
                        'roles' => ['Docente'],
                    ],
                    [
                        'actions' => ['obtener-gestiones', 'obtener-declaraciones-gestion', 'obtener-declaracion',
                                    'obtener-declaracion-jurada'],
                        'allow' => true,
                        'roles' => ['Docente', 'Administrador'],
                    ]
                ],
        ];

        return $behaviors;
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function beforeAction($action)
    {
        // Cambiamos el formato de respuesta a JSON
        Yii::$app->response->format = \yii\web\Response::FORMAT_JSON;
        if (Yii::$app->getRequest()->getMethod() === 'OPTIONS') {
            Yii::$app->getResponse()->getHeaders()->set('Allow', 'POST GET PUT');
            Yii::$app->end();
        }
        $this->enableCsrfValidation = false;

        // Mantenemos su comportamiento predeterminado
        return parent::beforeAction($action);
    }


    public function actionCrearDeclaracionJurada($id)
    {
        $body = Yii::$app->request->getBodyParams();

        $response = [
            'success' => true,
            'message' => 'Se creo correctamente la declaracion',
        ];

        $declaracionJurada = new DeclaracionJurada();

        $declaracionJurada->load($body['informacion'], '');
        $declaracionJurada->fk_usuario = $id;
        $transaction = $declaracionJurada::getDb()->beginTransaction();
        if ($declaracionJurada->save()) {
            $creacionCategorias = true;
            $creacionInstituciones = true;
            $idDeclaracion = $declaracionJurada->id_declaracion_jurada;
            $categorias = $body['categorias'];
            foreach ($categorias as $key => $categoria) {
                $declaracionJuradaCategoria = new DeclaracionJuradaCategoria();
                $declaracionJuradaCategoria->id_declaracion_jurada = $idDeclaracion;
                $declaracionJuradaCategoria->id_categoria = $categoria['id_categoria'];
                $declaracionJuradaCategoria->remuneracion = $categoria['remuneracion'];
                if (!$declaracionJuradaCategoria->save()) {
                    $transaction->rollBack();
                    $creacionCategorias = false;
                    $response = [
                        'success' => false,
                        'message' => 'No se pudo crear las categorias',
                    ];
                    break;
                }
            }

            if($creacionCategorias){
                $instituciones = $body['instituciones'];
                foreach ($instituciones as $key => $institucion) {
                    $declaracionJuradaInstitucion = new DeclaracionJuradaInstitucion();
                    $declaracionJuradaInstitucion->id_declaracion_jurada = $idDeclaracion;
                    $declaracionJuradaInstitucion->total_ganado = $institucion['total_ganado'];
                    $declaracionJuradaInstitucion->cargo = $institucion['cargo'];
                    $declaracionJuradaInstitucion->tipo = $institucion['tipo'];
                    
                    $idInstitucion = $this->buscarIdInstitucion($institucion['nombre']);
                    $declaracionJuradaInstitucion->id_institucion = $idInstitucion;
                    
                    
                    /*if ($institucion['id_institucion'] == null) {
                        if ($this->crearInstitucion($institucion['nombre'])) {
                            $idInstitucion = $this->buscarIdInstitucion($institucion['nombre']);
                            $declaracionJuradaInstitucion->id_institucion = $idInstitucion;
                        } else {
                            $transaction->rollBack();
                            $response = [
                                'success' => false,
                                'message' => 'No se pudo crear la institucion',
                            ];
                            break;
                        }
                    } else {
                        $declaracionJuradaInstitucion->id_institucion = $institucion['id_institucion'];
                    }*/

                    if (!$declaracionJuradaInstitucion->save()) {
                        $transaction->rollBack();
                        $creacionInstituciones = false;
                        $response = [
                            'success' => false,
                            'message' => 'No se pudo crear las instituciones',
                        ];
                        break;
                    }
                }
            }
            if($creacionCategorias == true && $creacionInstituciones == true){
                $transaction->commit();
            }
        } else {
            //$transaction->rollBack();
            $response = [
                'success' => false,
                'message' => 'No se pudo crear la declacion jurada',
            ];
        }
        return $response;
    }

    public function actionObtenerGestiones($id)
    {
        $gestion = DeclaracionJurada::find()->select('gestion')
            ->where(['fk_usuario' => $id])
            ->groupBy('gestion')
            ->orderBy('gestion DESC')->all();
        return [
            'success' => true,
            'message' => 'solictud realizada correctamente',
            'data' => $gestion
        ];
    }

    public function actionObtenerDeclaracionesGestion($id, $gestion)
    {
        $declaraciones = DeclaracionJurada::find()->select(['id_declaracion_jurada', 'fecha', 'estado'])
            ->where(['fk_usuario' => $id, 'gestion' => $gestion])
            ->orderBy('fecha DESC')
            ->all();

        return [
            'success' => true,
            'message' => 'solictud realizada correctamente',
            'data' => $declaraciones
        ];
    }

    public function actionObtenerDeclaracion($idDeclaracion)
    {
        $response = [];
        $declaracion = DeclaracionJurada::findOne($idDeclaracion);

        $categoria = $declaracion->getDeclaracionJuradaCategorias()->all();
        $categorias = Categoria::find()->all();
        $instituciones = $declaracion->getDeclaracionJuradaInstitucions()
            ->select(["declaracion_jurada_institucion.*", "institucion.nombre"])
            ->leftJoin("institucion", "institucion.id_institucion = declaracion_jurada_institucion.id_institucion")
            ->asArray()->all();
        $listaInstituciones = Institucion::find()->all();
        $usuario = $declaracion->usuario;
        
        $response = [
            'success' => true,
            'message' => 'La acción se realizó correctamente.',
            'usuario' => $usuario,
            'categoria' => $categoria,
            'categorias' => $categorias,
            'fecha' => $declaracion,
            'instituciones' => $instituciones,
            'listaInstituciones' => $listaInstituciones,
        ];

        return $response;
    }

    /*
    public function actionObtenerDeclaracionJurada($idDeclaracion)
    {
        $response = null;
        $declaracion = DeclaracionJurada::findOne($idDeclaracion);
        if($declaracion){
            $categorias = $declaracion->getDeclaracionJuradaCategorias()
                ->select(["declaracion_jurada_categoria.*", "categoria.nombre"])
                ->leftJoin("categoria", "categoria.id_categoria = declaracion_jurada_categoria.id_categoria")
                ->asArray()->all();
            $instituciones = $declaracion->getDeclaracionJuradaInstitucions()
                ->select(["declaracion_jurada_institucion.*", "institucion.nombre"])
                ->leftJoin("institucion", "institucion.id_institucion = declaracion_jurada_institucion.id_institucion")
                ->asArray()->all();
            $usuario = $declaracion->usuario;
            $response = [
                'success' => true,
                'message' => 'La acción se realizó correctamente.',
                'usuario' => $usuario,
                'categorias' => $categorias,
                'fecha' => $declaracion,
                'instituciones' => $instituciones,
            ];
        }else{
            $response=[
                'success' => true,
                'message' => 'No se encontro la declaracion jurada',
            ];
        }

        return $response;
    }
    */

    public function actionEditarDeclaracion($idDeclaracion)
    {
        $body = Yii::$app->request->getBodyParams();
        $response = [];
        //Obtenemos la fecha del objeto recibido en el body
        $fecha = $body['fecha'];

        //Buscamos el registro a editar
        $declaracionJurada = DeclaracionJurada::findOne(['id_declaracion_jurada' => $idDeclaracion]);

        $declaracionJurada->load($fecha,'');

        if ($declaracionJurada->save()) {
            //Obtenemos la lista de categorias asociadas al usuario del objeto recibido en el body
            $categoria = $body['categoria'];
            
            //Obtenemos las categorias que el usuario tiene asociadas actualmente de la base de datos
            $declaracionJuradaCategorias = DeclaracionJuradaCategoria::find()
                ->where("id_declaracion_jurada = {$idDeclaracion}")->all();

            //Recorremos las categorias obtenidas del body
            for ($i = 0; $i < count($categoria); $i++) {
                //Recorremos las categorias asociadas
                for ($j = 0; $j < count($declaracionJuradaCategorias); $j++) {
                    if ($categoria[$i]["id_categoria"] == $declaracionJuradaCategorias[$j]["id_categoria"]) {
                        //Buscamos en la bese de datos el registro donde el id de la lista recibida
                        //coincida con el id de la lista de la base de datos
                        $categoriaEditada = DeclaracionJuradaCategoria::findOne(["id_declaracion_jurada" => $idDeclaracion, "id_categoria" => $declaracionJuradaCategorias[$j]["id_categoria"]]);
                        //Cargamos los datos recibidos
                        $categoriaEditada->load($categoria[$i], '');
                        $categoriaEditada->save();
                        $j = count($declaracionJuradaCategorias);
                    }
                }
            }

            //EDITAR  INSTITUCIONES
            $institucionesNuevas = $body['instituciones']; //a subir

            //genera una lista solo con los id de la lista de las instituciones mandadas por el usuario
            //id representa un registro en la tabla declaracion_jurada_instituciones
            $institucionesNuevasAux = array();
            foreach ($institucionesNuevas as $institucion) {
                array_push($institucionesNuevasAux, $institucion['id']);
            }

            //obtenemos todos los registros asociados a una declaracion de la tabla declaracion_jurada_institucion
            $declaracionJuradaInstitucion = DeclaracionJuradaInstitucion::find()->select("id")
                        ->where("id_declaracion_jurada={$idDeclaracion}")->all(); //lo que tiene actualmente

            //genera una lista solo con los id de los registros de la tabla declaracion_jurada_institucion
            $declaracionJuradaInstitucionAux = array();
            foreach ($declaracionJuradaInstitucion as $institucion) {
                array_push($declaracionJuradaInstitucionAux,$institucion['id']);
            }

            //recoremos la lista con los id de las instituciones mandadas por el usuario
            //comparamos id de la lista de institucions con los id de la tabla de declaracion_jurada_institucion
            foreach ($institucionesNuevasAux as $key => $institucion) {
                $aux = $institucionesNuevas[$key];
                //si el id de la institucion se encuentra en la tabla declaracion_jurada_institucion
                //actualizamos el registro
                if(in_array($institucion, $declaracionJuradaInstitucionAux)){
                    $institucionEditada = DeclaracionJuradaInstitucion::findOne(["id"=>$institucionesNuevas[$key]['id']]);
                    $aux["id_institucion"]=$this->buscarIdInstitucion($aux["nombre"]);
                    unset($aux['nombre']);
                    $institucionEditada->load($aux, '');
                    $institucionEditada->save();
                //si el id de la institucion no se encuentra en la tabla declaracion_jurada_institucion o es nulo
                //creamos un nuevo registro
                }else{
                    $nuevaInstitucion = new DeclaracionJuradaInstitucion();
                    $nuevaInstitucion->id_declaracion_jurada=$idDeclaracion;
                    $nuevaInstitucion->id_institucion=$this->buscarIdInstitucion($aux["nombre"]);
                    $nuevaInstitucion->total_ganado=$aux["total_ganado"];
                    $nuevaInstitucion->cargo=strtoupper($aux["cargo"]);
                    $nuevaInstitucion->tipo=$aux["tipo"];
                    $nuevaInstitucion->save();
                }
            }

            //recoremos la lista con los id de la tabla declaracion_jurada_institucion
            //comparamos id de la tabla declaracion_jurada_institucion con los
            //id de la lista de instituciones mandadas por el usuario
            foreach ($declaracionJuradaInstitucionAux as $key => $institucion) {
                $aux = $declaracionJuradaInstitucion[$key];

                //si un registro no se encuentre entre las instituciones del usuario se elimina el registro
                if(!in_array($institucion, $institucionesNuevasAux)){
                    $institucion = DeclaracionJuradaInstitucion::findOne(["id"=>$declaracionJuradaInstitucion[$key]['id']]);
                    $institucion->delete();
                }
            }

            $response = [
                'success' => true,
                'message' => 'Se guardaron los cambios exitosamente.',
            ];

        } else {
            $response = [
                'success' => false,
                'message' => 'No se pudo actualizar la declacion jurada',
            ];
        }

        return $response;
    }

    public function actionActualizarDeclaracion($idDeclaracion)
    {
        $body = Yii::$app->request->getBodyParams();
        $response = $response = [
            'success' => true,
            'message' => 'Se guardaron los cambios exitosamente.',
        ];
        $actualizarCategoria = true;
        $actualizarInsitucion = true;

        //Obtenemos la fecha del objeto recibido en el body
        $fecha = $body['fecha'];

        //Buscamos el registro a editar
        $declaracionJurada = DeclaracionJurada::findOne(['id_declaracion_jurada' => $idDeclaracion]);
        $transaction = $declaracionJurada::getDb()->beginTransaction();

        $declaracionJurada->load($fecha,'');

        if ($declaracionJurada->save()) {
            //Obtenemos la lista de categorias asociadas al usuario del objeto recibido en el body
            $categorias = $body['categoria'];
            
            foreach ($categorias as $categoria) {
                $cat = DeclaracionJuradaCategoria::findOne(['id'=>$categoria['id']]);
                $cat->load($categoria,'');
                if(!$cat->save()){
                    $transaction->rollBack();
                    $actualizarCategoria = false;
                    $response = [
                        'success' => false,
                        'message' => 'No se pudo actualizar las categorias',
                    ];
                    break;
                }
            }

            if($actualizarCategoria){
                //EDITAR  INSTITUCIONES
                $institucionesNuevas = $body['instituciones']; //a subir

                $institucionesBaseDatosUsuario = DeclaracionJuradaInstitucion::find()
                            ->select('id')
                            ->where(['id_declaracion_jurada'=>$idDeclaracion])->all();
                $idInstitucionesNuevas = array();
                foreach ($institucionesNuevas as $institucion) {
                    array_push($institucionesNuevasAux, $institucion['id']);
                }

                foreach ($institucionesBaseDatosUsuario as $institucion) {
                    if(!in_array($institucion['id'], $idInstitucionesNuevas)){
                        $declaracionJuradaInstitucion = DeclaracionJuradaInstitucion::findOne(['id'=>$institucion['id']]);
                        $declaracionJuradaInstitucion->delete();
                    }
                }

                foreach ($institucionesNuevas as $institucion) {
                    if($institucion['id'] == null){
                        $nuevaInstitucion = new DeclaracionJuradaInstitucion();
                        $nuevaInstitucion->id_declaracion_jurada=$idDeclaracion;
                        $nuevaInstitucion->id_institucion=$this->buscarIdInstitucion($institucion['nombre']);
                        $nuevaInstitucion->total_ganado=$institucion['total_ganado'];
                        $nuevaInstitucion->cargo=strtoupper($institucion["cargo"]);
                        $nuevaInstitucion->tipo=$institucion["tipo"];
                        if(!$nuevaInstitucion->save()){
                            $transaction->rollBack();
                            $actualizarInsitucion = false;
                            $response = [
                                'success' => false,
                                'message' => 'No se pudo actualizar las instituciones',
                            ];
                            break;
                        }
                    }else{
                        $declaracionJuradaInstitucion = DeclaracionJuradaInstitucion::findOne(['id'=>$institucion['id']]);
                        $declaracionJuradaInstitucion->load($institucion,'');
                        if(!$declaracionJuradaInstitucion->save()){
                            $transaction->rollBack();
                            $actualizarInsitucion = false;
                            $response = [
                                'success' => false,
                                'message' => 'No se pudo actualizar las instituciones',
                            ];
                            break;
                        }
                    }
                }

                if($actualizarCategoria == true && $actualizarInsitucion == true){
                    $transaction->commit();
                }
            }
        } else {
            $response = [
                'success' => false,
                'message' => 'No se pudo actualizar la declacion jurada',
            ];
        }

        return $response;
    }

    public function actionEliminarDeclaracion($idDeclaracion){
        $response = [
            'success' => true,
            'message' => 'La declaracion se elimino correctamente'
        ];

        $declaracion = DeclaracionJurada::findOne(['id_declaracion_jurada'=>$idDeclaracion]);
        $transaction = $declaracion::getDb()->beginTransaction();
        $eliminarCategorias = true;
        $eliminarInstituciones = true;

        $categorias = $declaracion->getDeclaracionJuradaCategorias()->all();
        foreach ($categorias as $categoria) {
            $cat = DeclaracionJuradaCategoria::findOne(["id"=>$categoria->id]);
            
            if(!$cat->delete()){
                $transaction->rollBack();
                $eliminarCategorias=false;
                $response = [
                    'success' => false,
                    'message' => 'No se pudo eliminar las categorias'
                ];
                break;
            };
        }

        if($eliminarCategorias){
            $instituciones = $declaracion->getDeclaracionJuradaInstitucions()->all();
            foreach ($instituciones as $institucion) {
                $inst = DeclaracionJuradaInstitucion::findOne(['id'=>$institucion->id]);
                
                if(!$inst->delete()){
                    $transaction->rollBack();
                    $eliminarInstituciones=false;
                    $response = [
                        'success' => false,
                        'message' => 'No se pudo eliminar las instituciones'
                    ];
                    break;
                };
            }

            if($eliminarInstituciones){
                if($declaracion->delete()){
                    $transaction->commit();
                }else{
                    $transaction->rollBack();
                    $response = [
                        'success' => false,
                        'message' => 'No se pudo eliminar la declaracion'
                    ];
                }
            }
        }
        return $response;
    }

    /**
     * Crea una nueva institución en el caso de no encontrarse entre las instituciones ya registradas
     */
    public function actionCrearInstitucion(){

        $body = Yii::$app->request->getBodyParams();
        //Recibimos la lista de nuevas instituciones a guardar en la base de datos
        $instituciones = $body["nuevasInstituciones"];
        for($i = 0; $i < count($instituciones); $i++){
            $institucion = new Institucion();
            $institucion->load($instituciones[$i], '');
            $institucion->save();
        }
        $instituciones = Institucion::find()->all();//todas las instituciones
        $response = [
            'success' => true,
            'message' => 'Se creo la institución correctamente.',
            'instituciones' => $instituciones,
        ];
        return $response;
    }


    protected function crearInstitucion($nombre)
    {
        $institucion = new Institucion();
        $institucion->nombre = $nombre;
        if ($institucion->save()) {
            return $institucion->id_institucion;
        } else {
            return null;
        }
    }

    protected function buscarIdInstitucion($nombre)
    {
        $idInstitucion = Institucion::find()->select('id_institucion')
            ->where(['nombre' => strtoupper($nombre)])->one();
        if($idInstitucion){
            return $idInstitucion->id_institucion;
        }else{
            $nuevaInstitucionId = $this->crearInstitucion($nombre);
            return $nuevaInstitucionId;
        }
    }

    /**
     * El método buscarInstitución busca una insitución a través de su nombre en una
     * lista de instituciones que estan registradas en la base de datos.
     * @param string $nombre es el nombre de lal institución que se quiere buscar.
     * @param array $instituciones es la lista de instituciones que se tiene en la base de datos.
     * @return int Retorna el id de la institución que se essta buscando.
     */
    public function buscarInstitucion($nombre,$instituciones){
        $id_institucion = null;
        for($i =0;$i<count($instituciones); $i++){
            if(strtoupper($nombre) == $instituciones[$i]["nombre"]){
                $id_institucion = $instituciones[$i]["id_institucion"];
                $i = count($instituciones);
            }
        }
        return $id_institucion;
    }
}
