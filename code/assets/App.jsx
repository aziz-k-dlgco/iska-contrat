import React, { useEffect } from 'react';
import {
  Routes,
  Route,
  useLocation, BrowserRouter as Router
} from 'react-router-dom';

import './css/style.css';

// Import pages
import Dashboard from './pages/Dashboard';
import PageNotFound from './pages/utility/PageNotFound';
import ReactDOM from "react-dom/client";

function App() {
  const location = useLocation();

  useEffect(() => {
    document.querySelector('html').style.scrollBehavior = 'auto'
    window.scroll({ top: 0 })
    document.querySelector('html').style.scrollBehavior = ''
  }, [location.pathname]); // triggered on route change

  return (
    <>
      <Routes>
        <Route path="/" element={<PageNotFound />} />
      </Routes>
    </>
  );
}


ReactDOM.createRoot(document.getElementById('root')).render(
    <React.StrictMode>
      <Router>
        <App />
      </Router>
    </React.StrictMode>
);
