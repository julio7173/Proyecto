import React from 'react'
import DPA from './assets/img/DPA.png'
import Button from 'react-bootstrap/Button';
import { useNavigate, useLocation } from 'react-router';
import AuthService from '../services/authService';
import { NavLink } from 'react-router-dom';

const Navbar = () => {

    const navigate = new useNavigate()
    const location = useLocation();

    const cerrarSesion = () =>{
        AuthService.cerrarSesion()
    }

    return(
        <>
            <nav className="navbar" style={{backgroundColor:'#003377', color: 'white'}}>
                <div className="container-fluid">
                    <NavLink to="/home">
                        <img src= {DPA} alt="Logo"className="d-inline-block align-text-top" height='80'/>  
                    </NavLink>
                    <div className="col">
                            <h2 className="Navbar-a">DPA-UMSS</h2>
                            <h4 className='Navbar-a'>Dirección de Planificación Academica</h4>
                        </div>
                    {
                        location.pathname == "/home" || location.pathname == "/login" ?
                        <div className='d-flex'>
                            <NavLink to="/login">
                                <Button variant="light">Iniciar Sesión</Button>{' '}
                            </NavLink>
                        </div>: 
                        <div className='d-flex'>
                            <NavLink to="/">
                                <Button variant="light" onClick={cerrarSesion}>Cerrar Sesión</Button>{' '}
                            </NavLink>
                        </div>
                    }
                </div>
            </nav>     
       </>
        )
}
export default Navbar