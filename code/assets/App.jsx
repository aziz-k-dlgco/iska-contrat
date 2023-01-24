import React, { useContext, useEffect } from 'react';
import {
	Switch,
	Route,
	useLocation,
	BrowserRouter as Router
} from 'react-router-dom';
import { ToastContainer } from 'react-toastify';

import './css/style.css';
import 'flatpickr/dist/flatpickr.css';
import 'react-toastify/dist/ReactToastify.css';

// Import pages
import Home from './pages/Home';
import ContratHome from './pages/Contrat/ContratHome';
import ContratNew from './pages/Contrat/ContratNew';
import PageNotFound from './pages/utility/PageNotFound';
import ReactDOM from 'react-dom/client';
import Signin from './pages/Signin';
import RouteGuard from './components/Customs/RouteGuard';
import { LogoutContext, LogoutProvider } from './providers/LogoutContext';
import { DataProvider } from './providers/DataProvider';
import { JWTDataProvider } from './providers/JWTDataContext';
import ContratConsulter from './pages/Contrat/ContratConsulter';
import { ApiContext, ApiProvider } from './providers/ApiContext';
import { AuthContext, AuthProvider } from './providers/AuthContext';

function App() {
	const { isConnected } = useContext(AuthContext);

	// overflow hidden on body
	useEffect(() => {
		document.body.classList.add('overflow-hidden');
	});
	const location = useLocation();

	useEffect(() => {
		document.querySelector('html').style.scrollBehavior = 'auto';
		window.scroll({ top: 0 });
		document.querySelector('html').style.scrollBehavior = '';
	}, [location.pathname]); // triggered on route change

	return (
		<>
			<Switch>
				{isConnected ? (
					<>
						<Route exact path="/" component={Home} />
						<Route exact path="/contrat" component={ContratHome} />
						<Route path="/contrat/new" component={ContratNew} />
						<Route
							exact
							strict
							path="/contrat/consult/:id"
							component={ContratConsulter}
						/>
						<Route path="*" component={PageNotFound} />
					</>
				) : (
					<>
						<Route path="/connexion">
							<Signin />
						</Route>
						<Route path="*" component={Signin} />
					</>
				)}
			</Switch>
		</>
	);
}

ReactDOM.createRoot(document.getElementById('root')).render(
	<React.StrictMode>
		<AuthProvider>
			<ApiProvider>
				<Router>
					<App />
					<ToastContainer
						position="top-center"
						autoClose={2000}
						hideProgressBar={false}
						newestOnTop={false}
						closeOnClick
						pauseOnFocusLoss
						theme="light"
						draggable={false}
					/>
				</Router>
			</ApiProvider>
		</AuthProvider>
	</React.StrictMode>
);
