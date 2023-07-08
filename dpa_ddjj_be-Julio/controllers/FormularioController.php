<?php

namespace app\controllers;

use app\models\Categoria;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use app\models\InformacionFechas;
use app\models\Institucion;
use app\models\Usuario;
use app\models\UsuarioCategoria;
use app\models\UsuarioInstitucion;

class FormularioController extends  \yii\web\Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
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
        $this->enableCsrfValidation=false;
            
        // Mantenemos su comportamiento predeterminado	
        return parent::beforeAction($action); 	
    }

    /**
     * Obtener información de la declaración de un docente
     * @param int $id El ID del usuario del cual se obtendra la información
     */
    public function actionObtenerDeclaracion($id) 
    {
        $usuario = Usuario::findOne($id);
        if($usuario){
            $categoria = $usuario -> getUsuarioCategoria()->all();
            $categorias= Categoria::find()->all();
            if(!(count($categoria) == count($categorias))){
              $indice = count($categoria);
              for($i = $indice; $i < count($categorias); $i++){
                $usuarioCategoria = new UsuarioCategoria();
                $usuarioCategoria->id_usuario=$id;
                $usuarioCategoria->id_categoria=$categorias[$i]["id_categoria"];
                $usuarioCategoria->remuneracion=0;
                $usuarioCategoria->save();
              }
              $categoria = $usuario -> getUsuarioCategoria()->all();
            }
            $fecha = InformacionFechas::findOne(["fk_usuario"=>$id]);
            $instituciones = $usuario -> getUsuarioInstitucion()->select(["usuario_institucion.*", "institucion.nombre"])
                                ->leftJoin("institucion", "institucion.id_institucion = usuario_institucion.id_institucion")->asArray()->all();
            $listaInstituciones = Institucion::find()->all();
            $fecha = $fecha? $fecha:$this->crearFecha($id);
            $categoria = $categoria? $categoria:$this->crearUsuarioCategoria($id,$categorias);

            $response = [
                'success'=> true,
                'message' => 'La acción se realizó correctamente.',
                'usuario' => $usuario,
                'categoria' => $categoria,
                'categorias' => $categorias,
                'fecha' => $fecha,
                'instituciones' => $instituciones,
                'listaInstituciones' => $listaInstituciones,
            ];
        }else {
            // Si no existe lanzar error 404
            $response = [
                'success' => false,
                'message' => 'Docente no encontrado.'
            ];
        }
        return $response;
    }

    /**
     * Editar declaracion
     * @param int $id El ID del usuario del cual se editará su información
     */
    public function actionEditarDeclaracion($id) 
    {
        $body = Yii::$app->request->getBodyParams();
    //Obtenemos la fecha del objeto recibido en el body
        $fecha = $body['fecha'];
        
    // Buscamos el registro a editar
        $informacionFecha = InformacionFechas::findOne(["fk_usuario"=>$id]);

            $informacionFecha->load($fecha, '');
            $informacionFecha->save();

        //Obtenemos la lista de categorias asociadas al usuario del objeto recibido en el body
        $categoria = $body['categoria'];
        //Obtenemos las categorias que el usuario tiene asociadas actualmente de la base de datos
        $usuarioCategorias = UsuarioCategoria::find()->where("id_usuario={$id}")->all();

        //Recorremos las categorias obtenidas del body
        for($i=0; $i < count($categoria);$i++){
            //Recorremos las categorias asociadas 
            for($j=0; $j < count($usuarioCategorias);$j++){
                if($categoria[$i]["id_categoria"] == $usuarioCategorias[$j]["id_categoria"]){
                    //Buscamos en la bese de datos el registro donde el id de la lista recibida coincida con el id de la lista de la base de datos
                    $categoriaEditada = UsuarioCategoria::findOne(["id_usuario"=>$id,"id_categoria"=>$usuarioCategorias[$j]["id_categoria"]]);
                    //Cargamos los datos recibidos
                    $categoriaEditada->load($categoria[$i], '');
                    $categoriaEditada->save();
                    $j=count($usuarioCategorias);
                }
            }
        }
        
        //EDITAR  INSTITUCIONES
        $institucionesNuevas = $body['instituciones'];//a subir

        //genera una lista solo con los id de la lista de las instituciones mandadas por el usuario
        //id representa un registro en la tabla usuario_instituciones distinto al id del usuario pasado en la url
        $institucionesNuevasAux = array();
        foreach ($institucionesNuevas as $institucion) {
            array_push($institucionesNuevasAux, $institucion['id']);
        }

        //obtenemos todos los registros asociados a un usuario de la tabla usuario_institucion
        $usuarioInstitucion =UsuarioInstitucion::find()->select("id")->where("id_usuario={$id}")->all();//lo que tiene actualmente
        $instituciones = Institucion::find()->all();//todas las instituciones

        //genera una lista solo con los id de los registros de la tabla usuario_institucion
        $usuarioInstitucionAux = array();
        foreach ($usuarioInstitucion as $institucion) {
            array_push($usuarioInstitucionAux,$institucion['id']);
        }

        //recoremos la lista con los id de las instituciones mandadas por el usuario
        //comparamos id de la lista de institucions con los id de la tabla de usuario_institucion
        foreach ($institucionesNuevasAux as $key => $institucion) {
            $aux = $institucionesNuevas[$key];
            //si el id de la institucion se encuentra en la tabla usuario_institucion actualizamos el registro
            if(in_array($institucion, $usuarioInstitucionAux)){
                $institucionEditada = UsuarioInstitucion::findOne(["id"=>$institucionesNuevas[$key]['id']]);
                $aux["id_institucion"]=$this->buscarInstitucion($aux["nombre"],$instituciones);
                unset($aux['nombre']);
                $institucionEditada->load($aux, '');
                $institucionEditada->save();
            //si el id de la institucion no se encuentra en la tabla usuario_institucion o es nullo
            //creamos un nuevo registro
            }else{
                $nuevaInstitucion = new UsuarioInstitucion();
                $nuevaInstitucion->id_usuario=$id;
                $nuevaInstitucion->id_institucion=$this->buscarInstitucion($aux["nombre"],$instituciones);
                $nuevaInstitucion->total_ganado=$aux["total_ganado"];
                $nuevaInstitucion->cargo=strtoupper($aux["cargo"]);
                $nuevaInstitucion->tipo=$aux["tipo"];
                $nuevaInstitucion->save();
            }
        }

        //recoremos la lista con los id de la tabla usuario_institucion
        //comparamos id de la tabla usuario_institucion con los id de la lista de instituciones mandadas por el usuario
        foreach ($usuarioInstitucionAux as $key => $institucion) {
            $aux = $usuarioInstitucion[$key];

            //si un registro no se encuentre entre las instituciones del usuario se elimina el registro
            if(!in_array($institucion, $institucionesNuevasAux)){
                $institucion = UsuarioInstitucion::findOne(["id"=>$usuarioInstitucion[$key]['id']]);
                $institucion->delete();
            }
        }
        $response = [
            'success' => true,
            'message' => 'Se guardaron los cambios exitosamente.',
        ];
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


    /**
     * El método crear fecha crea una nueva fecha en la base de datos en caso de que el usuario no tenga una fecha asociada.
     * @param int $id es el id del usuario para el cual crearemos una nueva fecha.
     * @return object Retorna la fecha creada con los datos respectivos.
     */
    public function crearFecha($id){
        setlocale(LC_TIME, "spanish");
        $nuevaFecha = new InformacionFechas();
        $nuevaFecha->fecha=date("Y-m-d");
        $nuevaFecha->gestion=date("Y");
        $nuevaFecha->mes=strtoupper(strftime('%B'));
        $nuevaFecha->fk_usuario=$id;
        
        $nuevaFecha->save();

        return $nuevaFecha;
    }


     /**
     * El método crearUsuarioCategoría crea los registros de categorias asociadas al usuario asignandoles una remuneración igual 0.
     * @param int $id es el id del usuario para el cual crearemos una nueva fecha.
     * @param array $categorias es la lista de categorias que se asociarán al usuario.
     * @return array Retorna un arreglo con las categorias asociadas al usuario.
     */
    public function crearUsuarioCategoria($id,$categorias){
        $lista = [];
        // Para crear un nuevo registro instanciamos el modelo
        for($i=0; $i<count($categorias);$i++){
            $nuevaCategoria = new UsuarioCategoria();
            $nuevaCategoria->id_usuario=$id;
            $nuevaCategoria->id_categoria=$categorias[$i]["id_categoria"];
            $nuevaCategoria->remuneracion=0;
            $nuevaCategoria->save();
            array_push($lista,$nuevaCategoria);
        }
        return $lista;
    }



  /**
     * El método buscarInstitución busca una insitución a través de su nombre en una lista de instituciones que estan registradas en la base de datos.
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
