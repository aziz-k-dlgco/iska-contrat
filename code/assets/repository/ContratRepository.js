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
  console.log(formData);
  return axios.post("/api/contrat/new", formData, {
    headers: {
      "Content-Type": "multipart/form-data",
      Authorization: "Bearer " + localStorage.getItem("jwt"),
    },
  });
};

export { create };
