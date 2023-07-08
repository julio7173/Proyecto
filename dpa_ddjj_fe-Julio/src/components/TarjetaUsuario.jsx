import { useEffect, useState } from "react";
import { Card, Col, Row } from "react-bootstrap";
import DeclaracionJuradaServices from "../services/declaracionJuradaServices";

function TarjetaUsuario({nombre1, nombre2, paterno, materno, email, ci}) {

    return (
        <>
        {
            console.log(nombre1)
        }
            <Card style={{borderColor:'#003377', color:'#003377'}}>
                <Card.Body>
                    <Row>
                        <Col style={{textAlign:"right"}}>
                            Nombre: 
                        </Col>
                        <Col>
                            {`${nombre1} ${nombre2} ${paterno} ${materno}`}
                        </Col>
                    </Row>
                    <Row>
                        <Col style={{textAlign:"right"}}>
                            Email: 
                        </Col>
                        <Col>
                            {email}
                        </Col>
                    </Row>
                    <Row>
                        <Col style={{textAlign:"right"}}>
                            Ci: 
                        </Col>
                        <Col>
                            {ci}
                        </Col>
                    </Row>
                </Card.Body>
            </Card>
        </>
    )
}

export default TarjetaUsuario;