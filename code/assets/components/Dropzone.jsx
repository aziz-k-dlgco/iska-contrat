import React, { useEffect, useMemo, useState } from "react";
import { useDropzone } from "react-dropzone";

const baseStyle = {
  flex: 1,
  display: "flex",
  flexDirection: "column",
  alignItems: "center",
  padding: "20px",
  borderWidth: 2,
  borderRadius: 2,
  borderColor: "#eeeeee",
  borderStyle: "dashed",
  backgroundColor: "#fafafa",
  color: "#bdbdbd",
  outline: "none",
  transition: "border .24s ease-in-out",
};

const focusedStyle = {
  borderColor: "#2196f3",
};

const thumbsContainer = {
  display: "flex",
  flexDirection: "col",
  flexWrap: "wrap",
  marginTop: 16,
};

export default function Dropzone(props) {
  useDropzone();
  const files_icons_url = "/images/icons/";
  const [files, setFiles] = useState([]);
  const { getRootProps, getInputProps, isFocused } = useDropzone({
    maxFiles: 10,
    // Use file icon
    onDrop: (acceptedFiles) => {
      setFiles(
        acceptedFiles.map((file) =>
          Object.assign(file, {
            //preview: files_icons_url + file.name.split(".").pop() + ".png",
            preview: files_icons_url + "file.png",
          })
        )
      );
    },
  });

  const style = useMemo(
    () => ({
      ...baseStyle,
      ...(isFocused ? focusedStyle : {}),
    }),
    [isFocused]
  );

  const thumbs = files.map((file) => (
    /*
    preview
    file icon on top of the file name
    cross to delete file
     */
    <tr>
      <td className="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px">
        <img className="h-8 w-8 rounded-full object-cover" src={file.preview} />
      </td>
      <td className="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
        <div className="text-sm text-gray-900">{file.name}</div>
        <div className="text-sm text-gray-500">{file.size} bytes</div>
      </td>
      <td className="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap">
        <button
          className="text-red-600 hover:text-red-900"
          onClick={() => {
            setFiles(files.filter((f) => f !== file));
          }}
        >
          Supprimer
        </button>
      </td>
    </tr>
  ));

  useEffect(() => {
    // Make sure to revoke the data uris to avoid memory leaks, will run on unmount
    return () => files.forEach((file) => URL.revokeObjectURL(file.preview));
  }, []);

  return (
    <div className="container">
      <div {...getRootProps({ style })}>
        <input multiple {...getInputProps()} />
        <p>
          Glisser et déposer des fichiers ici, ou cliquer pour sélectionner les
          ajouter
        </p>
      </div>
      {thumbs.length > 0 && (
        <table className="table-auto w-full bg-slate-50">
          <thead className="text-xs font-semibold uppercase text-slate-500 border-t border-b border-slate-200">
            <tr>
              <th className="px-4 py-3">Fichier</th>
              <th className="px-4 py-3">Tailles</th>
              <th className="px-4 py-3">Actions</th>
            </tr>
          </thead>
          <tbody className="text-sm divide-y divide-slate-200">{thumbs}</tbody>
        </table>
      )}
    </div>
  );
}
