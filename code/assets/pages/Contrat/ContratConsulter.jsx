import React, { useEffect, useState } from "react";
import { useParams } from "react-router-dom";
import Sidebar from "../../partials/Sidebar";
import Header from "../../partials/Header";
import ContratNewFormv2 from "../../components/Customs/Forms/Contrat/ContratNewFormv2";

export default function ContratConsulter() {
  const title = "Consulter un contrat - Gestion Contractuelle";
  // Tableau des permissions
  const [perms, setPerms] = useState([]);
  const [sidebarOpen, setSidebarOpen] = useState(false);

  const params = useParams();

  useEffect(() => {}, []);

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
            <div className="flex flex-row mb-5">
              <div className="mb-4 sm:mb-0 ml-0">
                <h1 className="text-2xl md:text-3xl text-slate-800 font-bold">
                  {title}
                </h1>
              </div>
            </div>
          </div>
        </main>
      </div>
    </div>
  );
}
