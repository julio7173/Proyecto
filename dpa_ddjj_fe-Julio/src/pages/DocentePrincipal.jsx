import { useEffect, useState } from "react";
import DeclaracionJuradaServices from "../services/declaracionJuradaServices";
import Boton from "../components/Boton";
import { Col, Form, Row, Table } from "react-bootstrap";
import TablaDeclaracion from "../components/TablaDeclaracion";
import AuthService from "../services/authService";
import TarjetaUsuario from "../components/TarjetaUsuario";


function DocentePrincipal() {
    
    const [usuario, setUsuario] = useState(null)
    const [listaGestiones, setListaGestiones] = useState([])
    const [listaDeclaraciones, setListaDeclaraciones] = useState([])

    const loadListaDeclaraciones = async (id) => {
        const response = await DeclaracionJuradaServices.InformacionUsuario(id)
        if(response.data.success === true){
            setUsuario(response.data.data)
            console.log(response.data.data)
            const responseGestiones = await DeclaracionJuradaServices.getListaGestiones(id)
            if (responseGestiones.data.success === true) {
                const gestiones = responseGestiones.data.data
                setListaGestiones(gestiones)
                const responseDeclaraciones = await DeclaracionJuradaServices.getListaDeclaracionesGestion(id, gestiones[0].gestion)
                if (responseDeclaraciones.status === 200 && responseDeclaraciones.data.success === true) {
                    setListaDeclaraciones(responseDeclaraciones.data.data)
                } else {
                    console.log("error ", responseDeclaraciones)
                }
            } else {
                console.log("error ", responseGestiones)
            }
        } else {
            console.log("error ", response)
        }
    }

    useEffect(() => {
        let id = AuthService.getIdUsuario()
        loadListaDeclaraciones(id)
    }, [])

    const crearDeclaracion = () => {

    }


    return (
        <div className="container justify-content-center">
            <br/>
            {usuario ? <Row>
                <TarjetaUsuario nombre1={usuario.nombre1} nombre2={usuario.nombre2} paterno={usuario.paterno} materno={usuario.materno} email={usuario.email} ci={usuario.ci}></TarjetaUsuario>
            </Row> : null}
            <br/>
            <Row>
                <Col>
                    <Boton nombre='Nueva Declaracion' manejarClick={crearDeclaracion} name='Nueva Declaracion'></Boton>
                </Col>
                <Col>
                    <Row>
                        <Col style={{textAlign:"right"}}>
                            <label>Gestion: </label>
                        </Col>
                        <Col>
                            <Form>
                                <Form.Select size="sm">
                                    {
                                        listaGestiones.map(gestion => (
                                            <option>{gestion.gestion}</option>
                                        ))
                                    }
                                </Form.Select>
                            </Form>
                        </Col>
                    </Row>
                </Col>
            </Row>
            <br/>
            <Row className="justify-content-center">
                <TablaDeclaracion listaDeclaraciones={listaDeclaraciones} />
            </Row>
        </div>
    )
}

export default DocentePrincipal;