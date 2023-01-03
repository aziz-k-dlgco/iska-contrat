import React from "react";
import axios from "axios";
const create = (data, files) => {
  let formData = new FormData();
  Object.keys(data).forEach((key) => {
    formData.append(key, data[key]);
  });
  if (files) {
    Object.keys(files).forEach((key) => {
      formData.append(key, files[key]);
    });
  }

  const token = localStorage.getItem("jwt");
  const headers = {
    Authorization: `Bearer ${token}`,
  };

  return fetch("/api/contrat/new", {
    method: "POST",
    headers: headers,
    body: formData,
  });
};

export { create };
