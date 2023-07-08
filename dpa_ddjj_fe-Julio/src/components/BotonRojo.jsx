import categoria from '../styles/categoria.css'

function BotonRojo({nombre,manejarClick}) {
    return(
        <div className='container col-12 p-0'>
                    <button className='col-12 color_rojo m-2 text-white' style={{width:'190px', borderRadius:'8px'}}
                        onClick={manejarClick}
                    >
                        <p className='m-0'>{nombre}</p>
                    </button>
        </div>

    );
}
export default BotonRojo;