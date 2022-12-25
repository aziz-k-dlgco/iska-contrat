import React from "react";
import { createContext } from "react";

const API_PREFIX = "/api";

export const DataProviderContext = createContext();

export const DataProvider = ({ children }) => {
  const [dataStore, setDataStore] = React.useState({});

  // wait for fetch data from API, then give data to the callback
  const getData = async (dataUrl, callback) => {
    // add jwt token to the request if it exists
    const jwtToken = localStorage.getItem("jwt");
    const headers = jwtToken ? { Authorization: `Bearer ${jwtToken}` } : {};
    const response = await fetch(`${API_PREFIX}${dataUrl}`, { headers });
    const data = await response.json();
    callback(data);
  };

  const postData = async (dataUrl, data) => {
    // add jwt token to the request if it exists
    const jwtToken = localStorage.getItem("jwt");
    const headers = jwtToken ? { Authorization: `Bearer ${jwtToken}` } : {};
    // return the promise
    return fetch(`${API_PREFIX}${dataUrl}`, {
      method: "POST",
      headers: {
        "Content-Type": "application/json",
        ...headers,
      },
      body: JSON.stringify(data),
    });
  };

  return (
    <DataProviderContext.Provider value={{ getData }}>
      {children}
    </DataProviderContext.Provider>
  );
};
