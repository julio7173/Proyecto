
import { Routes, Route, useRoutes, Navigate } from "react-router-dom";
import Login from "./components/Login";
import Formulario from "./components/Formulario";
import AuthService from "./services/authService";
import LayoutPage from "./pages/LayoutPage";
import Home from "./pages/Home";
import DocentePrincipal from "./pages/DocentePrincipal";

/**
 * Crea la estructura de rutas y componentes del sistema
 * @returns Routes
 */
function Router() {
	let token = AuthService.getToken()
	let isAuth = token ? true : false;

    return useRoutes([
		{
			path: '/',
			element: <LayoutPage/>,
			children: [
				{
					path: '/',
					element: isAuth ? (
						<Navigate to="/declaraciones" />
					) : (
						<Navigate to="/home" />
					),
				},
				{
					path: 'home',
					element: !isAuth ? <Home /> : <Navigate to="/formulario" replace />,
				},
				{
					path: 'login',
					element: !isAuth ? <Login /> : <Navigate to="/formulario" replace />,
				},
				{
					path: 'declaraciones',
					element: isAuth ? <DocentePrincipal /> : <Navigate to="/" replace />,
				},
				{
					path: 'formulario/:idDeclaracion',
					element: isAuth ? <Formulario /> : <Navigate to="/" replace />,
				},
			],
		},
	]);
}

export default Router