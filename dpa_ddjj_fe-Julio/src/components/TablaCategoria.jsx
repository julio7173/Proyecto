import Categoria from "./inputs/Categoria";
import Total from "./inputs/Total";
import Form from 'react-bootstrap/Form';
import { useState, useEffect } from "react";

function TablaCategoria({categoria, cambiarCategoria,categorias,idUsuario}) {
  const [total, setTotal] = useState(0)

  //El metodo nos devuelve la suma de las remuneraciones de todas las categorias asociadas al usuario
  function sumar(){   
    let suma = 0
    for(var valor of categoria){
      suma += parseFloat(valor.remuneracion)
    }
    setTotal(suma.toFixed(2))
  }

  useEffect(()=>{
    if(Object.values(categoria).length > 0){
      sumar()
    }
}, [categoria])

    return (
      <div className='container col-10'>
        <div className='row justify-content-center'>
            <div className="col-12 color_text">
              <h3>A. Universidad Mayor de San Simón</h3>
            </div>
            <div className="col-10">
                <div className="col">
                  <div className='row cabecera_background p-2 text-white'>
                    <div className="col-8">
                        <Form.Label>Categoría</Form.Label>
                    </div>
                    <div className="col-4">
                        <Form.Label>Remuneración (Bs.)</Form.Label>
                    </div>
                  </div>
                </div>

                <div className="col">
                    {
                      categorias.map( cat => (
                        <Categoria
                          label = {cat.nombre}
                          categoria = {categoria}
                          cambiarCategoria={cambiarCategoria}
                          name = {cat.id_categoria}
                          key={cat.nombre}
                          id_usuario={idUsuario}
                        />
                      ))
                    }
                    {
                      <Total
                        label = {"TOTAL"}
                        suma = {total}
                      /> 
                    }
                </div>
            </div>
            <div className="col-12">
                <Form.Text id="passwordHelpBlock" muted>
                  IMPORTANTE: En caso de los ITEMS 1 y 2 (docentes y administrativos) se 
                  considera el total ganado mensual independdienntemente del tipo de contrato 
                  (indefinido, plazo fijo o servicios), en el caso de los servicios comprendidos 
                  del ITEM 3 AL ITEM 9 se considera la remuneracion de las fechas que se ha 
                  efectuado el servicio sin considerar si este ya ha sido pagado en efectivo o no. 
                  No debe incluirse el servicio para el cual se firma la presente declaracion. 
                </Form.Text>
            </div>
        </div>
      </div>
    );
  }
  export default TablaCategoria;