import AuthService from './authService';
import { API } from './connection'

const authHeader = () => {
    const token = AuthService.getToken();
    return { 'Authorization': `Bearer ${token}` }
}

const DeclaracionJuradaServices = {

    getListaGestiones: async (id) => {
        let response = null
        await API.get('/declaracion-jurada/obtener-gestiones', { params: { id: id }, headers: authHeader() })
            .then((res) => { response = res })
            .catch((err) => { response = err.response ? err.response : {} })
        return response
    },

    getListaDeclaracionesGestion: async (id, gestion) => {
        let response = null
        await API.get('/declaracion-jurada/obtener-declaraciones-gestion', { params: { id: id, gestion: gestion }, headers: authHeader() })
            .then((res) => { response = res })
            .catch((err) => { response = err.response ? err.response : {} })
        return response
    },

    getDeclaracion: async (idDeclaracion) => {
        let response = null
        await API.get('/declaracion-jurada/obtener-declaracion', { params: { idDeclaracion: idDeclaracion }, headers: authHeader() })
            .then((res) => { response = res })
            .catch((err) => { response = err.response ? err.response : {} })
        return response
    },

    editDeclaracion: async (id, data) => {
        let response = null
        await API.post('/declaracion-jurada/editar-declaracion', data, { params: { idDeclaracion: id }, headers: authHeader() })
            .then((res) => { response = res })
            .catch((err) => { response = err.response ? err.response : {} })
        return response
    },

    createInstitucion: async (instituciones) => {
        let response = null
        await API.post('/declaracion-jurada/crear-institucion', instituciones, { headers: authHeader() })
            .then((res) => { response = res })
            .catch((err) => { response = err.response ? err.response : {} })
        return response
    }, 



    /**
     * Servicios para obtener informacion de los usuarios
     */

    InformacionUsuario: async (idUsuario) => {
        let response = null
        await API.get('/usuario/informacion-usuario', {params: { idUsuario: idUsuario }, headers: authHeader() })
            .then((res) => { response = res })
            .catch((err) => { response = err.response ? err.response : {} })
        return response
    }
}



export default DeclaracionJuradaServices