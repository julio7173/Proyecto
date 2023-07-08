
import Form from 'react-bootstrap/Form';

function Categoria({label, categoria,cambiarCategoria,name,id_usuario}) {

  const onChange = (e) => {
    let categ = Array.from(categoria) //creamos una copia de la lista de categorias asociadas al usuario
    let filtro = categoria.filter(cat => cat["id_categoria"] == e.target.name) //filtramos la categoria que va a cambiar
    if(filtro.length != 0){ //si esa categoria existe asignamos la nueva remuneracion
      let objeto = filtro
      objeto[0].remuneracion = isNaN(parseFloat(e.target.value))?0:parseFloat(e.target.value)
      cambiarCategoria(categ)
    }else{ //si no existe añadimos un nuevo registro en las categorias asociadas al usuario
      //categoria.splice(categoria.length,0,{"id_usuario":{id_usuario},"id_categoria":name,"remuneracion":0})
    }
  }

  //Método que obtiene la remuneración del usuario para una determinada categoria desde la lista proporcionada
  function obtenerRemuneracion(){
    var remuneracion = 0
    if(categoria){
      //Recorremos la lista de categorias asociadas al usuario.
      for(var cat of categoria){
        if(cat.id_categoria == name){
            remuneracion = cat.remuneracion
        }
      }
    }
    return remuneracion.toFixed(2)
  }


  function validacion (e){
    if(isNaN(parseInt(e.key)) && e.key !== '.' && e.key !== 'Backspace'){
      e.preventDefault()
    }
  }

  return (
    <div className='container col p-0'>
      <div className='row categoria_background align-items-center p-1'>
       <div className="col-8">
            <Form.Label style={{ height: '25px' }}>{label}</Form.Label>
        </div>
        <div className="col-4" >
            <Form.Control style={{ height: '25px', textAlign:'end' }} type="text" placeholder="Bs."
              defaultValue={obtenerRemuneracion()} onChange={onChange} name={name} onKeyDown={validacion}
            />
        </div>
      </div>
    </div>
  );
}
export default Categoria;