import React, { useState } from "react";
import Sidebar from "../../partials/Sidebar";
import Header from "../../partials/Header";
import { Link } from "react-router-dom";
import Card from "../../components/Customs/Card";
import Table from "../../components/Customs/Table";
import Dropzone from "../../components/Dropzone";
import ContratNewForm from "../../components/Customs/Forms/Contrat/ContratNewForm";

export default function ContratNew() {
  const title = "Nouvelle demande de contrat - Gestion Contractuelle";
  document.title = title;
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
            <div className="flex flex-row mb-5">
              <div className="mb-4 sm:mb-0 ml-0">
                <h1 className="text-2xl md:text-3xl text-slate-800 font-bold">
                  {title}
                </h1>
              </div>
            </div>

            <ContratNewForm />
          </div>
        </main>
      </div>
    </div>
  );
}
