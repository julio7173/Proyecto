import AuthService from './authService';
import { API } from './connection'  

const authHeader = () =>{
    const token=AuthService.getToken();
    return {'Authorization': `Bearer ${token}`}
}

const FormularioService = {  	

getDeclaracion: async (id) => {     	
let response = null     	
await API.get('/declaracion-jurada/obtener-declaracion', { params: {idDeclaracion:8}, headers: authHeader() })         	
.then((res) => {response=res})         	
.catch((err) => {response=err.response ? err.response : {}})     	
return response 	
},  

editDeclaracion: async (id,data) => {     	
let response = null     	
await API.post('/formulario/editar-declaracion', data, { params: {id:id}, headers: authHeader()})         	
.then((res) => {response=res})         	
.catch((err) => {response=err.response ? err.response : {}})     	
return response 	
},  

createInstitucion: async (instituciones) => {     	
    let response = null     	
    await API.post('/formulario/crear-institucion', instituciones, {headers: authHeader()})         	
    .then((res) => {response=res})         	
    .catch((err) => {response=err.response ? err.response : {}})     	
    return response 	
    },  
}  



export default FormularioService
