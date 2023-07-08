import Form from 'react-bootstrap/Form';
import BotonRojo from "./BotonRojo";
import InputSelect from './inputs/InputSelect';

function TablaInstitucionPublica({instituciones, cambiarInstituciones, listaInstituciones, cambiarListaInstituciones,indice,index}) {
  let letras = "BCDEFGHIJKLMNÑOPQRSTUVWXYZ"
  function onSubmit(){
    instituciones.splice(indice,1)
    let inst = Array.from(instituciones)
    cambiarInstituciones(inst)
  }
  
  const onChange = (e) => {
    let campo = e.target.name //ubicamos que dato esta cambiando
    let objeto = instituciones[indice]//ubicamos que institucion es la que esta cambiando
    objeto[campo] = e.target.value //en ese dato asignamos el nuevo valor
    let institucion = Array.from(instituciones) //creamos una copia de la lista de instituciones asociadas al usuario
    institucion[indice] = objeto //en la posicion que cambio reemplazamos el objeto con los nuevos datos
    cambiarInstituciones(institucion) //en instituciones asignamos la nueva lista con los datos cambiados
  }

  function validacion (e){
    if(isNaN(parseInt(e.key)) && e.key !== '.' && e.key !== 'Backspace'){
      e.preventDefault()
    }
  }

    return (
      <div className='container col-10'>
        <div className='row justify-content-center'>
          <div className="col-12 color_text">
            <h3>{letras[index]}. Otra institución Pública</h3>
          </div>
          <div className='col-10'>
            <InputSelect
              label = "Nombre de la institución pública:"
              name = 'nombre'
              defaultValue = {instituciones[indice].nombre}
              indice = {indice}
              instituciones={instituciones}
              cambiarInstituciones={cambiarInstituciones}
              listaInstituciones = {listaInstituciones}
              cambiarListaInstituciones = {cambiarListaInstituciones}
            />
            <div className='row'>
            <div className="col-4 color_text">
                  <Form.Label style={{ height: '25px' }} >Cargo: </Form.Label>
              </div>
              <div className="col-2" >
                  <Form.Control name='cargo' style={{ height: '25px', background: '#D9D9D9' }} type="text" 
                                value={instituciones[indice].cargo.substring(0,1).toUpperCase() + instituciones[indice].cargo.toLowerCase().substring(1)} onChange={onChange}/>
              </div>
              <div className="col-4 color_text">
                  <Form.Label style={{ height: '25px' }}>Total ganado(Bs): </Form.Label>
              </div>
              <div className="col-2" >
                  <Form.Control name='total_ganado' style={{ height: '25px', background: '#D9D9D9', textAlign:'end' }}  type="text" placeholder="Bs." 
                                value={instituciones[indice].total_ganado} onChange={onChange} onKeyDown={validacion}/>
              </div>
            </div>
            <div className="row justify-content-end">
                <div className='col-3'>
                  <BotonRojo
                      nombre = "Eliminar institución"
                      manejarClick={onSubmit}
                  />
                </div>
            </div>
              
          </div>
        </div>
      </div>
    );
  }
  export default TablaInstitucionPublica;