import React, { useContext, useEffect, useState } from 'react';
import { useHistory, useParams } from 'react-router-dom';
import Sidebar from '../../partials/Sidebar';
import Header from '../../partials/Header';
import { useController, useForm } from 'react-hook-form';
import { create, findContrat } from '../../repository/ContratRepository';
import InputTextv2 from '../../components/Customs/Forms/Fields/InputTextv2';
import InputDropdownv2 from '../../components/Customs/Forms/Fields/InputDropdownv2';
import InputTextAreav2 from '../../components/Customs/Forms/Fields/InputTextAreav2';
import InputDatev2 from '../../components/Customs/Forms/Fields/InputDatev2';
import Dropzone from '../../components/Customs/Dropzone';
import { DataProviderContext } from '../../providers/DataProvider';
import { JWTDataContext } from '../../providers/JWTDataContext';
import { AuthContext } from '../../providers/AuthContext';
import DisplayDocuments from '../../components/Customs/DisplayDocuments';

export default function ContratConsulter() {
	const params = useParams();
	const title = 'Consulter un contrat - Gestion Contractuelle';
	// Tableau des permissions
	const [perms, setPerms] = useState([]);
	const [contrat, setContrat] = useState(null);
	const [show, setShow] = useState(false);
	const [sidebarOpen, setSidebarOpen] = useState(false);

	useEffect(() => {
		findContrat(params.id).then((data) => {
			setContrat(data);
			setShow(true);
			document.title = title;
		});
	}, []);

	return (
		<div className="flex h-screen overflow-hidden">
			{/* Sidebar */}
			<Sidebar
				sidebarOpen={sidebarOpen}
				setSidebarOpen={setSidebarOpen}
			/>

			{/* Content area */}
			<div className="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">
				{/*  Site header */}
				<Header
					sidebarOpen={sidebarOpen}
					setSidebarOpen={setSidebarOpen}
				/>

				<main>
					<div className="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">
						<div className="flex flex-row mb-5">
							<div className="mb-4 sm:mb-0 ml-0">
								<h1 className="text-2xl md:text-3xl text-slate-800 font-bold">
									{title}
								</h1>
							</div>
						</div>

						{show && <ContratConsult data={contrat} />}
					</div>
				</main>
			</div>
		</div>
	);
}

const ContratConsult = ({ data }) => {
	const [editForm, setEditForm] = useState(true);
	const { register, control, handleSubmit, errors } = useForm({
		defaultValues: {
			objet: data.object,
			'type-contrat': data.typeContrat,
			'departement-initiateur': data.departementInitiateur,
			'identite-cocontractant': data.identiteCocontractant,
			'clauses-particulieres': data.clausesParticulieres,
			'date-entree-vigueur': data.dateEntreeVigueur,
			'date-fin-contrat': data.dateFinContrat,
			'delai-denonciation': data.delaiDenonciation,
			'mode-facturation': data.modeFacturation,
			'periodicite-paiement': data.periodicitePaiement,
			'mode-paiement': data.modePaiement,
			'mode-renouvellement': data.modeRenouvellement,
			'objet-modification': data.objetModification,
			'details-modification': data.detailsModification
		}
	});

	const onSubmit = (data) => {
		console.log(data);
	};

	useEffect(() => {
		console.log(data);
	}, []);

	return (
		<>
			<div className="bg-white p-5 shadow-lg rounded-sm border border-slate-200 mb-5">
				<header className="mb-6">
					<h2 className="text-xl text-slate-800 mb-2">
						Lorem ipsum.
					</h2>
					<p>Lorem ipsum.</p>
					<button
						className={`btn btn-primary`}
						onClick={() => setEditForm(!editForm)}
					>
						Modifier
					</button>
				</header>
				<hr className="my-6 border-t border-slate-200" />
				<form onSubmit={handleSubmit(onSubmit)}>
					<h2 className="text-xl text-slate-800 mb-2">
						Identification du contrat
					</h2>
					<hr />
					<div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
						<div className="flex-1">
							<InputTextv2
								name="objet"
								control={control}
								disabled={editForm}
								label="Objet du contrat"
								placeholder="Objet du contrat"
								defaultValue=""
							/>
						</div>
						<div className="flex-1 flex flex-row gap-2">
							<div className="flex-1">
								<InputDropdownv2
									name="type-contrat"
									label="Type de contrat"
									control={control}
									disabled={editForm}
									data={data.typeContrat}
									isAnother={true}
								/>
							</div>
							<div className="flex-1">
								<InputDropdownv2
									name="departement-initiateur"
									label="Département initiateur"
									control={control}
									disabled={true}
									data={data.departementInitiateur}
									isAnother={false}
								/>
							</div>
						</div>
					</div>
					<div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
						<div className="flex-1">
							<InputTextv2
								name={'identite-cocontractant'}
								control={control}
								disabled={editForm}
								label={'Identité du co-contractant'}
								placeholder="Identité du co-contractant"
								defaultValue=""
							/>
						</div>
					</div>
					<h2 className="text-xl text-slate-800 my-2">
						Conditions du contrat
					</h2>
					<hr />
					<div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
						<div className="flex-1">
							<InputTextAreav2
								name="clauses-particulieres"
								control={control}
								disabled={editForm}
								label="Clauses particulières"
								placeholder="Clauses particulières"
								defaultValue=""
							/>
						</div>
					</div>
					<div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
						<div className="flex-1">
							<InputDatev2
								name={'date-entree-vigueur'}
								control={control}
								disabled={editForm}
								label="Date d'entrée en vigueur"
								defaultValue={new Date()}
							/>
						</div>
						<div className="flex-1">
							<InputDatev2
								name={'date-fin-contrat'}
								control={control}
								disabled={editForm}
								label="Date de fin de contrat"
								defaultValue={new Date()}
							/>
						</div>
						<div className="flex-1">
							<InputDropdownv2
								name="delai-denonciation"
								label="Délai de dénonciation ou de préavis"
								control={control}
								disabled={editForm}
								isAnother={false}
								data={[
									{ value: 1, label: '1 mois' },
									{ value: 2, label: '2 mois' },
									{ value: 3, label: '3 mois' },
									{ value: 4, label: '4 mois' },
									{ value: 5, label: '5 mois' },
									{ value: 6, label: '6 mois' },
									{ value: 7, label: '7 mois' },
									{ value: 8, label: '8 mois' },
									{ value: 9, label: '9 mois' },
									{ value: 10, label: '10 mois' },
									{ value: 11, label: '11 mois' },
									{ value: 12, label: '12 mois' }
								]}
							/>
						</div>
					</div>
					<div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
						<div className="flex-1">
							<InputDropdownv2
								name={'mode-facturation'}
								label={'Mode de facturation'}
								control={control}
								disabled={editForm}
								data={data.modeFacturation}
								isAnother={true}
							/>
						</div>
					</div>
					<div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
						<div className="flex-1">
							<InputDropdownv2
								name={'periodicite-paiement'}
								label={'Périodicité paiement'}
								control={control}
								disabled={editForm}
								data={data.periodicitePaiement}
								isAnother={true}
							/>
						</div>
					</div>
					<div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
						<div className="flex-1">
							<InputDropdownv2
								name={'mode-paiement'}
								label={'Mode de paiement'}
								control={control}
								disabled={editForm}
								data={data.modePaiement}
								isAnother={true}
							/>
						</div>
					</div>

					<h2 className="text-xl text-slate-800 my-2">
						Conditions de renouvellement
					</h2>
					<div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
						<div className="flex-1">
							<InputDropdownv2
								name={'mode-renouvellement'}
								label={'Mode de renouvellement'}
								control={control}
								disabled={editForm}
								data={data.modeRenouvellement}
								isAnother={false}
							/>
						</div>
					</div>

					<h2 className="text-xl text-slate-800 my-2">
						Conditions de modification
					</h2>
					<hr />
					<div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
						<div className="flex-1">
							<InputTextv2
								name={'objet-modification'}
								label={'Objet des conditions de modifications'}
								placeholder={
									'Objet des conditions de modifications'
								}
								control={control}
								disabled={editForm}
								defaultValue=""
							/>
						</div>
					</div>

					<div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
						<div className="flex-1">
							<InputTextAreav2
								name="details-modification"
								control={control}
								disabled={editForm}
								label="Détails des conditions de modifications"
								placeholder="Détails des conditions de modifications"
								defaultValue=""
							/>
						</div>
					</div>

					<div className="md:flex space-y-4 md:space-y-0 md:space-x-4 mt-5">
						<div className="flex-1">
							<DisplayDocuments documents={data.documents} />
						</div>
					</div>
				</form>
			</div>
		</>
	);
};
