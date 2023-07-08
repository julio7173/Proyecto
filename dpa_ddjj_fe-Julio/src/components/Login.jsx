import React, { useState } from "react";
import { Form, Button, Image } from "react-bootstrap";
import Cookies from 'universal-cookie';
import AuthService from "../services/authService";
import { useNavigate } from "react-router-dom";

const cookies= new Cookies();

function Login() {

  /*------------------------------------------VALIDA LOS CAMPOS------------------------------------------------------------------------------*/
  const [email, setEmail] = useState("");
  const [password, setPassword] = useState("");
  const [error, setError] = useState("");

  const navigate = useNavigate()

  const handleSubmit = async (e) => {
    e.preventDefault();
    // Aquí se puede validar el email y la contraseña y enviarlos al servidor
    if (email === "" || password === "") {
      setError("Debe llenar los campos necesarios");
    } else {
      setError("");
      // Aquí se puede redirigir al usuario a otra página
      const credenciales = {
        email: email,
        password: password
      }
      const response = await AuthService.iniciarSesion(credenciales);
      if (response.data.token) {
        cookies.set('token', response.data.token);
        cookies.set('id_usuario', response.data.id_usuario);
        navigate("/declaraciones")
      }else{
        setError(response.data.message)
      }
    }
  }
  /*-----------------------------------------CREA UNA SOMBRA EN EL CAMPO DEL CORREO AL ACERCAR EL CURSOR---------------------------------------------*/

  // Usamos un estado para guardar el boxShadow del Form.Control
  const [boxShadow1, setBoxShadow1] = useState("none");

  // Definimos una función que cambia el boxShadow a uno con sombra cuando se pasa el cursor sobre el Form.Control
  function handleMouseEnterCaja1() {
    setBoxShadow1("0 0 10px rgba(0,0,0,0.5)");
  }

  // Definimos otra función que cambia el boxShadow a ninguno cuando se quita el cursor del Form.Control
  function handleMouseLeaveCaja1() {
    setBoxShadow1("none");
  }
  /*-----------------------------------------CREA UNA SOMBRA EN EL CAMPO DE LA CONTRASEÑA AL ACERCAR EL CURSOR---------------------------------------------*/

  // Usamos un estado para guardar el boxShadow del Form.Control
  const [boxShadow2, setBoxShadow2] = useState("none");

  // Definimos una función que cambia el boxShadow a uno con sombra cuando se pasa el cursor sobre el Form.Control
  function handleMouseEnterCaja2() {
    setBoxShadow2("0 0 10px rgba(0,0,0,0.5)");
  }

  // Definimos otra función que cambia el boxShadow a ninguno cuando se quita el cursor del Form.Control
  function handleMouseLeaveCaja2() {
    setBoxShadow2("none");
  }
  /*-----------------------------------------CAMBIA EL COLOR DEL BOTON AL ACERCAR EL CURSOR------------------------------------------------*/

  // Usamos un estado para guardar el color del botón
  const [color, setColor] = useState("#e30613");

  // Definimos una función que cambia el color del botón a verde cuando se pasa el cursor sobre él
  function handleMouseEnter() {
    setColor("#003770");
  }

  // Definimos otra función que cambia el color del botón a blanco cuando se quita el cursor de él
  function handleMouseLeave() {
    setColor("#e30613");
  }
  /*---------------------------------------------------------------------------------------------------------*/

  return (
    <div className="container">
      <div className="row vh-100 justify-content-center align-items-center">
        <div className="col">
          <Form onSubmit={handleSubmit}>
            <h1 style={{ fontSize: "50px", fontFamily: "Open Sans", color: " #003770" }}>
              Iniciar sesión
            </h1>
            <p style={{ fontSize: "16px", fontFamily: "Open Sans", color: " #003770" }}>
              Ingrese sus datos
            </p>
            <Form.Group className="mb-3" controlId="formBasicEmail">
              <Form.Control
                type="email"
                placeholder="Correo electrónico"
                value={email}
                onChange={(e) => setEmail(e.target.value)}
                style={{ width: "205px", height: "40px", marginTop: "20px", borderRadius: "10px", boxShadow: boxShadow1 }}
                onMouseEnter={handleMouseEnterCaja1}
                onMouseLeave={handleMouseLeaveCaja1}
              />
            </Form.Group>

            <Form.Group className="mb-3" controlId="formBasicPassword">
              <Form.Control
                type="password"
                placeholder="Contraseña"
                value={password}
                onChange={(e) => setPassword(e.target.value)}
                style={{ width: "205px", height: "40px", marginTop: "20px", borderRadius: "10px", boxShadow: boxShadow2 }}
                onMouseEnter={handleMouseEnterCaja2}
                onMouseLeave={handleMouseLeaveCaja2}
              />
            </Form.Group>
            {error && <p variant="danger" style={{color:"red"}}>{error}</p>}
            <Button
              variant="primary"
              type="submit"
              style={{ backgroundColor: color, color: "white", fontFamily: "Open Sans", fontSize: "16px", borderRadius: "10px", width: "210px", height: "48px", marginTop: "20px" }}
              onMouseEnter={handleMouseEnter}
              onMouseLeave={handleMouseLeave}
            >
              Iniciar sesión
            </Button>
          </Form>
        </div>
        <div className="col" >
          <Image src="https://upload.wikimedia.org/wikipedia/commons/4/43/Marca_Vertical_Universidad_Mayor_de_San_Sim%C3%B3n_Cochabamba_Bolivia.png" fluid
            style={{ width: "500px", height: "500px"}} />
        </div>
      </div>
    </div>
  );
}

export default Login;