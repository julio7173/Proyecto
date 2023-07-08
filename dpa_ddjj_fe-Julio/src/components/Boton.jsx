import categoria from '../styles/categoria.css'

function Boton({nombre,manejarClick, name}) {
    return(
        <div className='container col-12 p-0'>
                    <button name={name} className='col-12 cabecera_background m-2 text-white' style={{width:'190px', borderRadius:'8px'}}
                        onClick={manejarClick}
                    >
                        <p className='m-0'>{nombre}</p>
                    </button>
        </div>
    );
}
export default Boton;