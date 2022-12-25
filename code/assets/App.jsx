import React, { useContext, useEffect } from "react";
import {
  Switch,
  Route,
  useLocation,
  BrowserRouter as Router,
} from "react-router-dom";

import "./css/style.css";
import "flatpickr/dist/flatpickr.css";

// Import pages
import Home from "./pages/Home";
import ContratHome from "./pages/Contrat/ContratHome";
import ContratNew from "./pages/Contrat/ContratNew";
import PageNotFound from "./pages/utility/PageNotFound";
import ReactDOM from "react-dom/client";
import Signin from "./pages/Signin";
import RouteGuard from "./components/Customs/RouteGuard";
import { LogoutContext, LogoutProvider } from "./providers/LogoutContext";
import { DataProvider } from "./providers/DataProvider";
import { JWTDataProvider } from "./providers/JWTDataContext";

function App() {
  const { logoutHandler } = useContext(LogoutContext);

  // overflow hidden on body
  useEffect(() => {
    document.body.classList.add("overflow-hidden");
  });
  const location = useLocation();

  useEffect(() => {
    document.querySelector("html").style.scrollBehavior = "auto";
    window.scroll({ top: 0 });
    document.querySelector("html").style.scrollBehavior = "";
  }, [location.pathname]); // triggered on route change

  return (
    <>
      <Switch>
        <Route path="/connexion">
          <Signin />
        </Route>
        <RouteGuard exact path="/" component={Home} />
        <RouteGuard exact path="/contrat" component={ContratHome} />
        <RouteGuard exact path="/contrat/new" component={ContratNew} />
      </Switch>
    </>
  );
}

ReactDOM.createRoot(document.getElementById("root")).render(
  <React.StrictMode>
    <LogoutProvider>
      <DataProvider>
        <JWTDataProvider>
          <Router>
            <App />
          </Router>
        </JWTDataProvider>
      </DataProvider>
    </LogoutProvider>
  </React.StrictMode>
);
