import { API } from './connection'
import Cookies from 'universal-cookie'

const cookies= new Cookies()
/**Servicios para la autenticacion del usuario */
const AuthService = {
    /**
     * Servicio para iniciar sesion, envia una solicitud con las credenciales del usuario a la API
     * este deveulve la respuesta de la api como un objeto
     * @param {*} credenciales 
     * @returns Object
     */
    iniciarSesion: async ( credenciales = {}) => {    
        let response = null;
        await API.post("/usuario/iniciar-sesion", credenciales)
            .then((res) => { response = res})
            .catch((err) => { response = err.response ? err.response : {}})
        return response
    },

    /**
     * Servicio para cerrar sesion, elimina los datos del usuario de las cookies
     */
    cerrarSesion: async () => {
        cookies.remove("id_usuario")
        cookies.remove("token")
    },

    /**
     * Servicio que retorna el token del usuario que se encuentra almacenado en las cookies
     * @returns string
     */
    getToken: () => {
        return cookies.get("token")
    },

    /**
     * Servicio que retorna el id del usario que se encuentra almacenado en las cookies
     * @returns string
     */
    getIdUsuario: () => {
        return cookies.get("id_usuario")
    },
}

export default AuthService;