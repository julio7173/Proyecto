import Form from 'react-bootstrap/Form';
import TablaCategoria from "./TablaCategoria";
import TablaDatos from "./TablaDatos";
import Boton from "./Boton";
import TablaInstitucionPublica from "./TablaInstitucionPublica";
import TablaRentaJubilacion from "./TablaRentaJubilacion";
import { useState, useEffect } from "react";
import FormatoDocPDF from "../reports/FormatoDocPDF";
import AuthService from '../services/authService';
import { useParams } from "react-router-dom";
import DeclaracionJuradaServices from '../services/declaracionJuradaServices';

function Formulario() {
    let contadorJubilacion = 0

    const [declaracion, setDeclaracion] = useState()
    const [usuario, setUsuario] = useState({})//Carga los datos del usuario
    const [fecha, setFecha] = useState({})//Carga la fecha de la declaracion asociada al usuario
    const [categoria, setCategoria] = useState([])//Carga las categorias de la tabla usuario_categoria asociadas al usuario
    const [categorias, setCategorias] = useState([])//Carga la lista de las categorias que existen registradas en la base de datos (tabla categoria)
    const [instituciones, setInstituciones] = useState([])//Carga las instituciones de la tabla usuario_instituciones asociadas al usuario
    const [listaInstituciones, setListaInstituciones] = useState([])//Carga la lista de las instituciones que existen registradas en la base de datos (tabla institucion)
    const {idDeclaracion} = useParams()

    const loadDeclaracion = async (id) => {
        const response = await DeclaracionJuradaServices.getDeclaracion(id)
        if (response.status === 200) {
            setDeclaracion(response.data)
            setUsuario(response.data.usuario)
            setFecha(response.data.fecha)
            setCategoria(response.data.categoria)
            setCategorias(response.data.categorias)
            setInstituciones(response.data.instituciones)
            setListaInstituciones(response.data.listaInstituciones)
        } else {
        }
    }

    const editarDeclaracion = async (id, nuevaDeclaracion) => {
        const response = await DeclaracionJuradaServices.editDeclaracion(id, nuevaDeclaracion)
        if (response.status === 200) {
        } else {
        }
    }


    useEffect(() => {
        let id = idDeclaracion
        loadDeclaracion(id)
    }, [])

    //llama al servicio crear institucion en el caso de que se declare una institucion que no este registrada en la base de datos
    const newInstitucion = async (nuevasInstituciones) => {
        const response = await DeclaracionJuradaServices.createInstitucion(
            {
                "nuevasInstituciones": nuevasInstituciones
            }
        )
        if (response.status === 200) {
            setListaInstituciones(response.data.instituciones)
        } else {
        }
    }

    //boton "añadir institución"
    function onSubmitI() {
        let instPub = Array.from(instituciones)
        instPub.push({ id: null, nombre: "", cargo: "", total_ganado: 0, tipo: "I" })
        setInstituciones(instPub)
    }

    //boton "añadir jubilación"
    function onSubmitR() {
        if (contadorJubilacion === 1) {
            alert("Solo se puede tener una renta de jubilación.")
        } else {
            let instPub = Array.from(instituciones)
            instPub.push({ id: null,nombre: "", cargo: "", total_ganado: 0, tipo: "R" })
            setInstituciones(instPub)
        }
    }

    //Permite saber si todas las instituciones declaradas por el docente estan registradas en la base de datos.
    function existe() {
        var existe = true;
        var nuevasInstituciones = [];
        for (var i = 0; i < instituciones.length; i++) {
            for (var j = 0; j < listaInstituciones.length; j++) {
                if ((instituciones[i].nombre).toUpperCase() === (listaInstituciones[j].nombre).toUpperCase()) {
                    existe = true;
                    j = listaInstituciones.length
                } else {
                    existe = false;
                }
            }
            if (!existe) {
                nuevasInstituciones.push({ "nombre": (instituciones[i].nombre).toUpperCase() })
            }
        }
        return nuevasInstituciones;
    }


    //boton guardar
    function onSubmitG() {
        let nuevasInstituciones = existe()//el metodo devuelve una lista con las instituciones que se declararoon que no estan registradas en nuestra base de datos
        if (nuevasInstituciones.length === 0) {//si el tamaño de la lista es 0 significa que no existen nuevas instituciones
        } else {
            newInstitucion(nuevasInstituciones)//si es distinto de 0 quiere decir que existen instituciones nuevas y se las guarda en la base de datos.
        }
        editarDeclaracion(idDeclaracion, { "fecha": fecha, "categoria": categoria, "instituciones": instituciones })
        alert("Guardado exitosamente.")
    }
    
    return (
        <div className='container'>
            <div className="row justify-content-center">
                <div className='row justify-content-end'>
                    <div className="col-12 color_text text-center mt-3">
                        <h2>FORMULARIO DE DECLARACIÓN JURADA</h2>
                    </div>
                    <div className="col-12 mt-2 mb-5">
                        {
                            declaracion ?
                                <TablaDatos
                                    usuario={usuario}
                                    cambiarUsuario={setUsuario}
                                    fecha={fecha}
                                    cambiarFecha={setFecha}
                                />
                                : null
                        }

                    </div>
                    <div className="col-12">
                        {
                            categoria && categorias ?
                                <TablaCategoria
                                    categoria={categoria}
                                    cambiarCategoria={setCategoria}
                                    categorias={categorias}
                                    idUsuario={usuario.id_usuario}
                                />
                                : null
                        }
                    </div>
                </div>
                <div className="col-4">
                    <div className="row justify-content-center">
                        <div className="col-6">
                            <Boton
                                nombre="Añadir institución"
                                manejarClick={onSubmitI}
                                name="I"
                            />
                        </div>
                        <div className="col-6">
                            <Boton
                                nombre="Añadir jubilación"
                                manejarClick={onSubmitR}
                                name="R"
                            />
                        </div>
                    </div>
                </div>

                <div className="row">
                    {
                            instituciones.filter(institucion => institucion.tipo === "I").map((institucion, index) => (
                                <TablaInstitucionPublica
                                    instituciones={instituciones}
                                    cambiarInstituciones={setInstituciones}
                                    listaInstituciones={listaInstituciones}
                                    cambiarListaInstituciones={setListaInstituciones}
                                    indice={instituciones.indexOf(institucion)}
                                    index={index}
                                    key={"inst" + index}
                                />
                            ))
                    }
                </div>
                <div className="row">
                    {
                        instituciones ?
                            instituciones.filter(institucion => institucion.tipo === "R").map(institucion => (
                                contadorJubilacion++ !== 1 ?
                                    <TablaRentaJubilacion
                                        instituciones={instituciones}
                                        cambiarInstituciones={setInstituciones}
                                        listaInstituciones={listaInstituciones}
                                        cambiarListaInstituciones={setListaInstituciones}
                                        indice={instituciones.indexOf(institucion)}
                                        longitud={instituciones.length}
                                        key={institucion.id}
                                    />
                                    : null
                            ))
                            : null
                    }
                </div>
                {
                    instituciones ?
                        <div className="col-10">
                            <Form.Text id="passwordHelpBlock" muted>
                                NOTA.- El monto declarado corresponde al total ganado (no liquido pagable), es decir el monto percibido antes de
                                deducciones, aportes laborales e impuestos. Se considera institución pública a toda aquella que se encuentra en los
                                artículos 3,4 y 5 de la ley 1178 del 20 de julio de 1990.
                            </Form.Text>
                        </div>
                        : null
                }
                <div className="col-4">
                    <div className="row justify-content-center">
                        <div className="col-6">
                            <Boton
                                nombre="Guardar"
                                manejarClick={onSubmitG}
                            />
                        </div>
                        <div className="col-6">
                            <FormatoDocPDF
                                fecha={fecha}
                                usuario={usuario}
                                categoria={categoria}
                                categorias={categorias}
                                guardar = {onSubmitG}
                                instituciones={instituciones}
                                nombre={'Imprimir'}
                            />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
export default Formulario;