import {Fecha,Nombre,Gestion,Numero,Seleccion} from "./inputs/Datos"

function TablaDatos({usuario, cambiarUsuario, fecha, cambiarFecha}) {
    return (
        <div className='container col-10'>
            <div className='row justify-content-end'>
                <div className="col-4">
                    <Fecha
                        fecha={fecha}
                        cambiarFecha={cambiarFecha}
                    />
                </div>
            </div>
            <div className='row justify-content-end'>
                <div className="col-8">
                    <Nombre
                        usuario={usuario}
                        cambiarUsuario={cambiarUsuario}
                    />
                </div>
                <div className="col-4">
                    <Numero
                        usuario={usuario}
                        cambiarUsuario={cambiarUsuario}
                    />
                </div>
            </div>    
            <div className='row justify-content-end'>
                <div className="col-8">
                    <Seleccion
                        fecha = {fecha}
                        cambiarFecha = {cambiarFecha}
                    />
                </div>
                <div className="col-4">
                    <Gestion
                        fecha = {fecha}
                        cambiarFecha = {cambiarFecha}
                    />
                </div>
            </div> 
            <div className='row justify-content-end'>
                <div className="col-10">
                    
                </div>
            </div> 
      </div>
    );
  }
  export default TablaDatos;