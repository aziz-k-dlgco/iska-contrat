import React, { useContext, useEffect, useState } from 'react';

import Sidebar from '../../partials/Sidebar';
import Header from '../../partials/Header';
import Table from '../../components/Customs/Table';
import Card from '../../components/Customs/Card';
import { Link } from 'react-router-dom';
import loaderSVG from '../../misc/loader.svg';
import { ApiContext } from '../../providers/ApiContext';

function ContratHome() {
	const { Proxy } = useContext(ApiContext);
	const title = 'Accueil - Gestion Contractuelle';
	document.title = title;
	const [sidebarOpen, setSidebarOpen] = useState(false);
	const [loadingTable, setLoadingTable] = useState(true);
	const [loadingStats, setLoadingStats] = useState(true);
	const [data, setData] = useState([]);
	const [stats, setStats] = useState([]);

	useEffect(() => {
		Proxy()
			.get('/api/contrat')
			.then((res) => {
				setData(res.data);
				setLoadingTable(false);
			})
			.catch((err) => {
				console.log(err);
				setLoadingTable(false);
			});

		Proxy()
			.get('/api/contrat/stats')
			.then((res) => {
				setStats(res.data);
				setLoadingStats(false);
			})
			.catch((err) => {
				console.log(err);
				setLoadingStats(false);
			});
	}, []);

	const Loader = () => {
		return (
			<div className="flex flex-col items-center justify-center h-80">
				<img src={loaderSVG} className="w-80" alt="Loading..." />
			</div>
		);
	};

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

							{/* two buttons aligned right */}
							<div className="flex flex-row ml-auto">
								<div className="mb-4 sm:mb-0 ml-0">
									<Link to="/contrat/new">
										<button className="btn bg-indigo-500 hover:bg-indigo-600 text-white">
											<svg
												className="w-4 h-4 fill-current opacity-50 shrink-0"
												viewBox="0 0 16 16"
											>
												<path d="M15 7H9V1c0-.6-.4-1-1-1S7 .4 7 1v6H1c-.6 0-1 .4-1 1s.4 1 1 1h6v6c0 .6.4 1 1 1s1-.4 1-1V9h6c.6 0 1-.4 1-1s-.4-1-1-1z" />
											</svg>
											<span className="hidden xs:block ml-2">
												Nouvelle demande de contrat
											</span>
										</button>
									</Link>
								</div>
								{/*<div className="mb-4 sm:mb-0 ml-2">
									<button className="btn bg-emerald-500 hover:bg-emerald-600 text-white">
										 SVG Clock
										<svg
											className="w-4 h-4 fill-current opacity-50 shrink-0"
											viewBox="0 0 16 16"
										>
											<path d="M8 1C4.1 1 1 4.1 1 8s3.1 7 7 7 7-3.1 7-7-3.1-7-7-7zm0 12.5c-2.5 0-4.5-2-4.5-4.5S5.5 3.5 8 3.5 12.5 5.5 12.5 8 10.5 12.5 8 12.5zM8 5c-.3 0-.5.1-.7.3-.4.4-.4 1 0 1.4l3 3c. 2.5 2.5 0 0 0 .7-.3.3-.7.3-1 0l-3-3c-.2-.2-.4-.3-.7-.3z" />
										</svg>
										<span className="hidden xs:block ml-2">
											Activités recentes
										</span>
									</button>
								</div>*/}
							</div>
						</div>

						{loadingStats ? (
							<Loader />
						) : (
							<div className="grid grid-cols-12 gap-6 mb-5">
								{stats.length !== 0 &&
									stats.map((stat, index) => (
										<Card
											key={index}
											title={stat.lib}
											value={stat.value}
											// if stat.description is not null, then display tooltip
											tooltip={
												stat.description
													? stat.description
													: null
											}
										/>
									))}
							</div>
						)}

						{loadingTable ? <Loader /> : <Table data={data} />}
					</div>
				</main>
			</div>
		</div>
	);
}

export default ContratHome;
