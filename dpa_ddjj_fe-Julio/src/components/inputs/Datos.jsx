
import Form from 'react-bootstrap/Form';


//Componente para ingresar la fecha en el formulario.
function Fecha({fecha,cambiarFecha}) {
  const onChange = (e) => {
    cambiarFecha({...fecha, fecha : e.target.value})
}

  return (
    <div className='container col p-0'>
      <div className='row align-items-center p-1'>
        <div className="col-6">
              <Form.Label className='m-0' style={{ height: '25px' }}>Fecha de llenado: </Form.Label>
          </div>
          <div className="col-6" >
          {
            fecha? 
              <Form.Control style={{ height: '25px', background: '#D9D9D9' }} type="date"
                defaultValue={fecha.fecha} onChange={onChange}
              />
            :
              <Form.Control style={{ height: '25px', background: '#D9D9D9' }} type="date"
                defaultValue={null} onChange={onChange}
              />
            }
          </div>
        </div>
      
    </div>
  );
}

//Componente donde se carga el nombre del usuario en el formulario.
function Nombre({usuario, cambiarUsuario}) {
    return (
      <div className='container col p-0'>
        <div className='row align-items-center p-1'>
         <div className="col-1">
              <Form.Label style={{ height: '25px' }}>Yo: </Form.Label>
          </div>
          <div className="col-11" >
              <Form.Control style={{ height: '25px', background: '#D9D9D9' }} type="text" disabled
                defaultValue={usuario.nombre1 + " " + usuario.nombre2 + " " + usuario.paterno + " " +usuario.materno}
              />
          </div>
        </div>
      </div>
    );
  }

//Componente para ingresar la gestión en el formulario.
  function Gestion({fecha, cambiarFecha}) {
    const onChange = (e) => {
      cambiarFecha({...fecha, gestion : e.target.value})
  }
    return (
      <div className='container col p-0'>
        <div className='row align-items-center p-1'>
           <div className="col-6">
                <Form.Label style={{ height: '25px' }} >DE LA GESTIÓN: </Form.Label>
            </div>
            <div className="col-6" >
              {
                fecha?
                  <Form.Control style={{ height: '25px', background: '#D9D9D9' }} type="number"
                      defaultValue = {fecha.gestion} onChange={onChange}
                  />
                :
                <Form.Control style={{ height: '25px', background: '#D9D9D9' }} type="number"
                    defaultValue = {new Date().getFullYear()} onChange={onChange}
                />
              }
            </div>
        </div>
      </div>
    );
  }

//Componente donde se carga el CI en el formulario.
  function Numero({usuario, cambiarUsuario}) {
    return (
      <div className='container col p-0'>
        <div className='row align-items-center p-1'>
         <div className="col-6">
              <Form.Label style={{ height: '25px' }}>CI: </Form.Label>
          </div>
          <div className="col-6" >
              <Form.Control style={{ height: '25px', background: '#D9D9D9' }} type="number"
                defaultValue={usuario.ci} disabled
              />
          </div>
        </div>
      </div>
    );
  }


  //Componente donde se selecciona el mes en el formulario.
  function Seleccion({fecha, cambiarFecha}) {
    const onChange = (e) => {
        cambiarFecha({...fecha, mes : e.target.value})
    }
    return (
      <div className='container col p-0'>
        <div className='row align-items-center p-1'>
         <div className="col-5">
              <Form.Label style={{ height: '25px' }}>DECLARO PERCIBIR EN EL MES DE: </Form.Label>
          </div>
          <div className="col-7" >
              <select className="form-select form-select-sm ba" onChange={onChange} aria-label=".form-select-sm example" style={{ background:'#d9d9d9'}}>
                  {
                    fecha?
                    <option defaultValue>{fecha.mes}</option>
                    :
                    <option defaultValue>ENERO</option>
                  }
                  <option value="ENERO">ENERO</option>
                  <option value="FEBRERO">FEBRERO</option>
                  <option value="MARZO">MARZO</option>
                  <option value="ABRIL">ABRIL</option>
                  <option value="MAYO">MAYO</option>
                  <option value="JUNIO">JUNIO</option>
                  <option value="JULIO">JULIO</option>
                  <option value="AGOSTO">AGOSTO</option>
                  <option value="SEPTIEMBRE">SEPTIEMBRE</option>
                  <option value="OCTUBRE">OCTUBRE</option>
                  <option value="NOVIEMBRE">NOVIENBRE</option>
                  <option value="DICIEMBRE">DICIEMBRE</option>
              </select>
          </div>
        </div>
      </div>
    );
  }

export {Fecha,Nombre,Gestion, Numero, Seleccion};