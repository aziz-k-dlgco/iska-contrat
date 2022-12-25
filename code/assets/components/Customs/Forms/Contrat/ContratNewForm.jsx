import React, { useContext, useEffect } from "react";
import { useForm } from "react-hook-form";
import Flatpickr from "react-flatpickr";
import InputDate from "../Fields/InputDate";
import InputText from "../Fields/InputText";
import InputDropdown from "../Fields/InputDropdown";
import Dropzone from "../../../Dropzone";
import { DataProviderContext } from "../../../../providers/DataProvider";
import Tooltip from "../../../Tooltip";
import { JWTDataContext } from "../../../../providers/JWTDataContext";

const ContratNewForm = () => {
  const { getData } = useContext(DataProviderContext);
  const { getJWT } = useContext(JWTDataContext);
  const [typeContrat, setTypeContrat] = React.useState([]);
  const [modeFacturation, setModeFacturation] = React.useState([]);
  const [modePaiement, setModePaiement] = React.useState([]);
  const [departement, setDepartement] = React.useState([]);
  const [modeRenouvellement, setModeRenouvellement] = React.useState([]);
  const [periodicitePaiement, setPeriodicitePaiement] = React.useState([]);

  const [objet, setObjet] = React.useState("");
  const {
    register,
    formState: { errors },
    handleSubmit,
  } = useForm();
  const onSubmit = (data) => console.log(data);

  const decodeJWT = getJWT();

  /*useEffect(() => {
    console.log(objet);
  }, [objet]);*/

  useEffect(() => {
    getData("/contrat/type-contrat", (data) => setTypeContrat(data));
    getData("/contrat/mode-facturation", (data) => setModeFacturation(data));
    getData("/contrat/mode-paiement", (data) => setModePaiement(data));
    getData("/contrat/mode-renouvellement", (data) =>
      setModeRenouvellement(data)
    );
    getData("/account/departement", (data) => setDepartement(data));
    getData("/contrat/periodicite-paiement", (data) =>
      setPeriodicitePaiement(data)
    );
  }, []);

  return (
    <div className="bg-white p-5 shadow-lg rounded-sm border border-slate-200">
      <header className="mb-6">
        <h2 className="text-xl text-slate-800 mb-2">
          Initer une demande de contrat
        </h2>
        <p>
          Remplissez les champs ci-dessous pour initialiser une demande de
          contrat. Tous les champs sont obligatoires. Les champs laissés vides
          seront remplis par défaut par "Non renseigné".
        </p>
      </header>
      <hr className="my-6 border-t border-slate-200" />
      <form onSubmit={handleSubmit(onSubmit)}>
        <h2 className="text-xl text-slate-800 mb-2">
          Identification du contrat
        </h2>
        <hr />
        <div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
          <div className="flex-1">
            <InputText
              name={"objet"}
              label={"Objet du contrat"}
              placeholder={"Objet du contrat"}
              onChange={(e) => setObjet(e.target.value)}
            />
          </div>
          <div className="flex-1 flex flex-row gap-2">
            <div className="flex-1">
              <InputDropdown
                name={"type-contrat"}
                label={"Type de contrat"}
                data={typeContrat}
                isAnother={true}
                onChange={(e) => console.log(e.target.value)}
              />
            </div>
            <div className="flex-1">
              {decodeJWT.role === "ROLE_USER_JURIDIQUE" ||
              decodeJWT.role === "ROLE_MANAGER_JURIDIQUE" ? (
                <InputDropdown
                  name={"departement-initiateur"}
                  label={"Département Initiateur"}
                  data={departement}
                />
              ) : (
                <InputText
                  name={"departement-initiateur"}
                  label={"Département Initiateur"}
                  placeholder={"Identité du co-contractant"}
                  defaultValue={decodeJWT.departement}
                  disabled={true}
                />
              )}
            </div>
          </div>
        </div>
        <div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
          <div className="flex-1">
            <InputText
              name={"identite-cocontractant"}
              label={"Identité du co-contractant"}
              placeholder={"Identité du co-contractant"}
            />
          </div>
        </div>
        <h2 className="text-xl text-slate-800 my-2">Conditions du contrat</h2>
        <hr />
        <div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
          <div className="flex-1">
            <label
              className="block text-sm font-medium mb-1"
              htmlFor="clauses-particulieres"
            >
              Clauses particulières
            </label>
            <textarea
              id="card-name"
              className="form-input w-full"
              placeholder="Patrick"
            />
          </div>
        </div>
        <div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
          <div className="flex-1">
            <label
              className="block text-sm font-medium mb-1"
              htmlFor="date-entree-vigueur"
            >
              Date d'entrée en vigueur
            </label>
            <InputDate />
          </div>
          <div className="flex-1">
            <label
              className="block text-sm font-medium mb-1"
              htmlFor="date-fin-contrat"
            >
              Date de fin de contrat
            </label>
            <InputDate />
          </div>
          <div className="flex-1">
            <InputDropdown
              name={"delai-denonciation"}
              label={"Délai de dénonciation ou de préavis"}
              data={[
                { value: "1", label: "1 mois" },
                { value: "2", label: "2 mois" },
                { value: "3", label: "3 mois" },
                { value: "4", label: "4 mois" },
                { value: "5", label: "5 mois" },
                { value: "6", label: "6 mois" },
                { value: "7", label: "7 mois" },
                { value: "8", label: "8 mois" },
                { value: "9", label: "9 mois" },
                { value: "10", label: "10 mois" },
                { value: "11", label: "11 mois" },
                { value: "12", label: "12 mois" },
              ]}
            />
          </div>
        </div>
        <div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
          <div className="flex-1">
            <InputDropdown
              name={"mode-facturation"}
              label={"Mode de facturation"}
              data={modeFacturation}
              isAnother={true}
              onChange={(e) => console.log(e.target.value)}
            />
          </div>
          <div className="flex-1">
            <InputDropdown
              name={"periodicite-paiement"}
              label={"Périodicité paiement"}
              data={periodicitePaiement}
              isAnother={true}
              onChange={(e) => console.log(e.target.value)}
            />
          </div>
          <div className="flex-1">
            <InputDropdown
              name={"mode-paiement"}
              label={"Mode de paiement"}
              data={modePaiement}
              isAnother={true}
              onChange={(e) => console.log(e.target.value)}
            />
          </div>
        </div>
        <h2 className="text-xl text-slate-800 my-2">
          Conditions de renouvellement
        </h2>
        <hr />
        <div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
          <div className="flex-1">
            <InputDropdown
              name={"mode-renouvellement"}
              label={"Mode de renouvellement"}
              data={modeRenouvellement}
            />
          </div>
        </div>
        <h2 className="text-xl text-slate-800 my-2">
          Conditions de modification
        </h2>
        <hr />
        <div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
          <div className="flex-1">
            <InputText
              name={"objet-modification"}
              label={"Objet des conditions de modifications"}
              placeholder={"Objet des conditions de modifications"}
            />
          </div>
        </div>
        <div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
          <div className="flex-1">
            <label
              className="block text-sm font-medium mb-1"
              htmlFor="details-modification"
            >
              Détails des conditions de modifications
            </label>
            <textarea
              id="details-modification"
              className="form-input w-full"
              placeholder="Détails des conditions de modifications"
            />
          </div>
        </div>
        <h2 className="text-xl text-slate-800 my-2">Pièces jointes</h2>

        <p>
          Nous vous recommandons de copier les fichiers dans un dossier pour
          pouvoir les sélectionner plus facilement.
        </p>
        <hr />
        <div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
          <div className="flex-1">
            <Dropzone />
          </div>
        </div>
      </form>
    </div>
  );
};

export default ContratNewForm;
