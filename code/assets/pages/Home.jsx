import React, { useState } from 'react';

import Sidebar from '../partials/Sidebar';
import Header from '../partials/Header';
import WelcomeBanner from '../partials/dashboard/WelcomeBanner';
import DashboardAvatars from '../partials/dashboard/DashboardAvatars';
import FilterButton from '../components/DropdownFilter';
import Datepicker from '../components/Datepicker';
import DashboardCard01 from '../partials/dashboard/DashboardCard01';
import DashboardCard02 from '../partials/dashboard/DashboardCard02';
import DashboardCard03 from '../partials/dashboard/DashboardCard03';
import DashboardCard04 from '../partials/dashboard/DashboardCard04';
import DashboardCard05 from '../partials/dashboard/DashboardCard05';
import DashboardCard06 from '../partials/dashboard/DashboardCard06';
import DashboardCard07 from '../partials/dashboard/DashboardCard07';
import DashboardCard08 from '../partials/dashboard/DashboardCard08';
import DashboardCard09 from '../partials/dashboard/DashboardCard09';
import DashboardCard10 from '../partials/dashboard/DashboardCard10';
import DashboardCard11 from '../partials/dashboard/DashboardCard11';
import {Link} from "react-router-dom";

function Home() {
  document.title = "Accueil";
  const [sidebarOpen, setSidebarOpen] = useState(false);

  return (
    <div className="flex h-screen overflow-hidden">

      {/* Sidebar */}
      <Sidebar sidebarOpen={sidebarOpen} setSidebarOpen={setSidebarOpen} />

      {/* Content area */}
      <div className="relative flex flex-col flex-1 overflow-y-auto overflow-x-hidden">

        {/*  Site header */}
        <Header sidebarOpen={sidebarOpen} setSidebarOpen={setSidebarOpen} />

        <main>
          <div className="px-4 sm:px-6 lg:px-8 py-8 w-full max-w-9xl mx-auto">

            {/* Welcome banner */}
            <WelcomeBanner />

              <div className="grid grid-cols-12 gap-6">
                  <div
                      className="col-span-full xl:col-span-4 2xl:col-span-4 bg-white shadow-md rounded-sm border border-slate-200">
                      <div className="flex flex-col h-full p-5">
                          <div className="grow">
                              <header className="flex items-center mb-4">
                                  <div
                                      className="w-10 h-10 rounded-full shrink-0 bg-gradient-to-tr from-indigo-500 to-indigo-300 mr-3">
                                      <svg className="w-10 h-10 fill-current text-white" viewBox="0 0 40 40">
                                          <path
                                              d="M26.946 18.005a.583.583 0 00-.53-.34h-6.252l.985-3.942a.583.583 0 00-1.008-.52l-7 8.167a.583.583 0 00.442.962h6.252l-.984 3.943a.583.583 0 001.008.52l7-8.167a.583.583 0 00.087-.623z"></path>
                                      </svg>
                                  </div>
                                  <h3 className="text-lg text-slate-800 font-semibold">Demande de contrats / Contrats</h3></header>
                              <div className="text-sm">
                                  Effectuez une demande de contrat ou consultez vos contrats en cours.
                              </div>
                          </div>
                          <footer className="mt-4">
                              <div className="flex flex-wrap justify-between items-center">
                                  <div className="flex space-x-3" style={{visibility: 'hidden'}}>
                                      <div className="flex items-center text-slate-400">
                                          <svg className="w-4 h-4 shrink-0 fill-current mr-1.5" viewBox="0 0 16 16">
                                              <path
                                                  d="M14.14 9.585a2.5 2.5 0 00-3.522 3.194c-.845.63-1.87.97-2.924.971a4.979 4.979 0 01-1.113-.135 4.436 4.436 0 01-1.343 1.682 6.91 6.91 0 006.9-1.165 2.5 2.5 0 002-4.547h.002zM10.125 2.188a2.5 2.5 0 10-.4 2.014 5.027 5.027 0 012.723 3.078c.148-.018.297-.028.446-.03a4.5 4.5 0 011.7.334 7.023 7.023 0 00-4.469-5.396zM4.663 10.5a2.49 2.49 0 00-1.932-1.234 4.624 4.624 0 01-.037-.516 4.97 4.97 0 011.348-3.391 4.456 4.456 0 01-.788-2.016A6.989 6.989 0 00.694 8.75c.004.391.04.781.11 1.166a2.5 2.5 0 103.86.584z"></path>
                                          </svg>
                                          <div className="text-sm text-slate-500">4K+</div>
                                      </div>
                                      <div className="flex items-center text-amber-500">
                                          <svg className="w-4 h-4 shrink-0 fill-current mr-1.5" viewBox="0 0 16 16">
                                              <path
                                                  d="M10 5.934L8 0 6 5.934H0l4.89 3.954L2.968 16 8 12.223 13.032 16 11.11 9.888 16 5.934z"></path>
                                          </svg>
                                          <div className="text-sm text-amber-600">4.7</div>
                                      </div>
                                  </div>
                                  <Link to='/contrat'>
                                      <button
                                          className="btn-sm border-slate-200 bg-indigo-500 text-white hover:border-slate-300 shadow-sm flex items-center">
                                          <span>Continuer</span>
                                          <svg className="w-3 h-3 shrink-0 fill-current text-white ml-2"
                                               viewBox="0 0 12 12">
                                                <path
                                                    d="M4.293 1.293a1 1 0 011.414 0l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414-1.414L7.586 6l-3.293-3.293a1 1 0 010-1.414z"></path>
                                          </svg>
                                      </button>
                                  </Link>
                              </div>
                          </footer>
                      </div>
                  </div>
                  <div
                      className="col-span-full xl:col-span-4 2xl:col-span-4 bg-white shadow-md rounded-sm border border-slate-200">
                      <div className="flex flex-col h-full p-5">
                          <div className="grow">
                              <header className="flex items-center mb-4">
                                  <div
                                      className="w-10 h-10 rounded-full shrink-0 bg-gradient-to-tr from-emerald-500 to-emerald-300 mr-3">
                                      <svg className="w-10 h-10 fill-current text-white" viewBox="0 0 40 40">
                                          <path
                                              d="M26.946 18.005a.583.583 0 00-.53-.34h-6.252l.985-3.942a.583.583 0 00-1.008-.52l-7 8.167a.583.583 0 00.442.962h6.252l-.984 3.943a.583.583 0 001.008.52l7-8.167a.583.583 0 00.087-.623z"></path>
                                      </svg>
                                  </div>
                                  <h3 className="text-lg text-slate-800 font-semibold">MaterialStack</h3></header>
                              <div className="text-sm">Lorem ipsum dolor sit amet eiusmod sed do eiusmod tempor.</div>
                          </div>
                          <footer className="mt-4">
                              <div className="flex flex-wrap justify-between items-center">
                                  <div className="flex space-x-3">
                                      <div className="flex items-center text-slate-400">
                                          <svg className="w-4 h-4 shrink-0 fill-current mr-1.5" viewBox="0 0 16 16">
                                              <path
                                                  d="M14.14 9.585a2.5 2.5 0 00-3.522 3.194c-.845.63-1.87.97-2.924.971a4.979 4.979 0 01-1.113-.135 4.436 4.436 0 01-1.343 1.682 6.91 6.91 0 006.9-1.165 2.5 2.5 0 002-4.547h.002zM10.125 2.188a2.5 2.5 0 10-.4 2.014 5.027 5.027 0 012.723 3.078c.148-.018.297-.028.446-.03a4.5 4.5 0 011.7.334 7.023 7.023 0 00-4.469-5.396zM4.663 10.5a2.49 2.49 0 00-1.932-1.234 4.624 4.624 0 01-.037-.516 4.97 4.97 0 011.348-3.391 4.456 4.456 0 01-.788-2.016A6.989 6.989 0 00.694 8.75c.004.391.04.781.11 1.166a2.5 2.5 0 103.86.584z"></path>
                                          </svg>
                                          <div className="text-sm text-slate-500">4K+</div>
                                      </div>
                                      <div className="flex items-center text-amber-500">
                                          <svg className="w-4 h-4 shrink-0 fill-current mr-1.5" viewBox="0 0 16 16">
                                              <path
                                                  d="M10 5.934L8 0 6 5.934H0l4.89 3.954L2.968 16 8 12.223 13.032 16 11.11 9.888 16 5.934z"></path>
                                          </svg>
                                          <div className="text-sm text-amber-600">4.7</div>
                                      </div>
                                  </div>
                                  <button
                                      className="btn-sm border-slate-200 hover:border-slate-300 shadow-sm flex items-center">
                                      <svg className="w-3 h-3 shrink-0 fill-current text-emerald-500 mr-2"
                                           viewBox="0 0 12 12">
                                          <path
                                              d="M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z"></path>
                                      </svg>
                                      <span>Connected</span></button>
                              </div>
                          </footer>
                      </div>
                  </div>
                  <div
                      className="col-span-full xl:col-span-4 2xl:col-span-4 bg-white shadow-md rounded-sm border border-slate-200">
                      <div className="flex flex-col h-full p-5">
                          <div className="grow">
                              <header className="flex items-center mb-4">
                                  <div
                                      className="w-10 h-10 rounded-full shrink-0 bg-gradient-to-tr from-sky-500 to-sky-300 mr-3">
                                      <svg className="w-10 h-10 fill-current text-white" viewBox="0 0 40 40">
                                          <path
                                              d="M26.946 18.005a.583.583 0 00-.53-.34h-6.252l.985-3.942a.583.583 0 00-1.008-.52l-7 8.167a.583.583 0 00.442.962h6.252l-.984 3.943a.583.583 0 001.008.52l7-8.167a.583.583 0 00.087-.623z"></path>
                                      </svg>
                                  </div>
                                  <h3 className="text-lg text-slate-800 font-semibold">MaterialStack</h3></header>
                              <div className="text-sm">Lorem ipsum dolor sit amet eiusmod sed do eiusmod tempor.</div>
                          </div>
                          <footer className="mt-4">
                              <div className="flex flex-wrap justify-between items-center">
                                  <div className="flex space-x-3">
                                      <div className="flex items-center text-slate-400">
                                          <svg className="w-4 h-4 shrink-0 fill-current mr-1.5" viewBox="0 0 16 16">
                                              <path
                                                  d="M14.14 9.585a2.5 2.5 0 00-3.522 3.194c-.845.63-1.87.97-2.924.971a4.979 4.979 0 01-1.113-.135 4.436 4.436 0 01-1.343 1.682 6.91 6.91 0 006.9-1.165 2.5 2.5 0 002-4.547h.002zM10.125 2.188a2.5 2.5 0 10-.4 2.014 5.027 5.027 0 012.723 3.078c.148-.018.297-.028.446-.03a4.5 4.5 0 011.7.334 7.023 7.023 0 00-4.469-5.396zM4.663 10.5a2.49 2.49 0 00-1.932-1.234 4.624 4.624 0 01-.037-.516 4.97 4.97 0 011.348-3.391 4.456 4.456 0 01-.788-2.016A6.989 6.989 0 00.694 8.75c.004.391.04.781.11 1.166a2.5 2.5 0 103.86.584z"></path>
                                          </svg>
                                          <div className="text-sm text-slate-500">4K+</div>
                                      </div>
                                      <div className="flex items-center text-amber-500">
                                          <svg className="w-4 h-4 shrink-0 fill-current mr-1.5" viewBox="0 0 16 16">
                                              <path
                                                  d="M10 5.934L8 0 6 5.934H0l4.89 3.954L2.968 16 8 12.223 13.032 16 11.11 9.888 16 5.934z"></path>
                                          </svg>
                                          <div className="text-sm text-amber-600">4.7</div>
                                      </div>
                                  </div>
                                  <button
                                      className="btn-sm border-slate-200 hover:border-slate-300 shadow-sm flex items-center">
                                      <svg className="w-3 h-3 shrink-0 fill-current text-emerald-500 mr-2"
                                           viewBox="0 0 12 12">
                                          <path
                                              d="M10.28 1.28L3.989 7.575 1.695 5.28A1 1 0 00.28 6.695l3 3a1 1 0 001.414 0l7-7A1 1 0 0010.28 1.28z"></path>
                                      </svg>
                                      <span>Connected</span></button>
                              </div>
                          </footer>
                      </div>
                  </div>

              </div>

          </div>
        </main>

      </div>

    </div>
  );
}

export default Home;