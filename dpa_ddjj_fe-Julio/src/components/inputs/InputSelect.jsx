
import Form from 'react-bootstrap/Form';
import { useEffect, useState } from 'react';

function InputSelect({label,name, defaultValue, indice, instituciones, cambiarInstituciones, listaInstituciones, cambiarListaInstituciones}) {
    const [value, setValue] = useState("")
    const [res, setRes] = useState([])//Guarda la lista de los nombres de las instituciones que coinciden con los caracteres ingresados en el input
    
    const onChange = (e) => {
        setValue(e.target.value.toUpperCase())
        let objeto = instituciones[indice]
        objeto[name] = e.target.value
        let institucion = Array.from(instituciones)
        institucion[indice] = objeto
        cambiarInstituciones(institucion)
    }

//Filtra aquellas instituciones en las cuales su nombre coincide con los caracteres ingresados en el input
    const filtrar = () => {
        setRes(listaInstituciones.filter(lista => lista.nombre.includes(value)))
    }

    function onClick(nombre){
        setValue(nombre)
        let objeto = instituciones[indice]
        objeto[name] = nombre
        let institucion = Array.from(instituciones)
        institucion[indice] = objeto
        cambiarInstituciones(institucion)
    }

    useEffect(()=>{
        filtrar()
    }, [value])

    useEffect(()=>{
        setValue(defaultValue)
    }, [defaultValue])

  return (
    <div className='container col-12 p-0'>
      <div className='row align-items-center'>
                <div className="col-4 color_text">
                    <Form.Label style={{ height: '25px' }}>{label}</Form.Label>
                </div>
                <div className="col-8" >
                    <div className="dropdown col-12">
                        <Form.Control value={value} className='dropdown-toggle' onChange={onChange} data-bs-toggle="dropdown" 
                                        name='nombre' style={{ height: '25px', background: '#D9D9D9' }} type="text"/>
                        <ul className="dropdown-menu col-12" style={{ background: '#D9D9D9' }}>
                            {
                                res.map(inst => (
                                    <li  name={inst.nombre} onClick={() => onClick(inst.nombre)} style={{ height: '25px', background: '#D9D9D9' }} key={inst.id_institucion} className="dropdown-item">{inst.nombre}</li>
                                ))
                            }
                        </ul>
                    </div>
                </div>
        </div>
      
    </div>
  );
}

export default InputSelect;