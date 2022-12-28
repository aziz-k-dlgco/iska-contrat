import React from "react";
import axios from "axios";
const create = (data) => {
  let formData = new FormData();
  Object.keys(data).forEach((key) => {
    formData.append(key, data[key]);
  });
  if (data["pj"]) {
    data["pj"].forEach((pj, index) => {
      formData.append("pj_" + index, pj);
    });
    formData.delete("pj");
  }
  return axios.post("/api/contrat/new", formData, {
    headers: {
      "Content-Type": "multipart/form-data",
      Authorization: "Bearer " + localStorage.getItem("jwt"),
    },
  });
};

export { create };
