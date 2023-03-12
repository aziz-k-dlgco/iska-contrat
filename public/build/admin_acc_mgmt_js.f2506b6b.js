"use strict";(self.webpackChunk=self.webpackChunk||[]).push([[831],{7595:(t,e,a)=>{a(1249);var s=a(6455),i=a.n(s),r=a(9755);a.g.$=a.g.jQuery=r;var o=a(6695),n="/admin/acc_mgmt";o.module("adminApp",[]).config(["$interpolateProvider",function(t){t.startSymbol("//").endSymbol("//")}]).controller("mainCtrl",["$scope","$http","$timeout","$log",function(t,e,a,s){t.Toast=i().mixin({toast:!0,position:"top-end",showConfirmButton:!1,timer:5e3}),t.roles=[{id:0,lib:"ROLE_ADMIN"},{id:1,lib:"ROLE_JURIDIQUE"},{id:2,lib:"ROLE_USER_MANAGER"},{id:3,lib:"ROLE_USER_BOSS_JURIDIQUE"},{id:4,lib:"ROLE_USER"},{id:5,lib:"ROLE_JURIDIQUE2"},{id:6,lib:"ROLE_JURIDIQUE3"},{id:7,lib:"ROLE_JURIDIQUE4"}],t.dataReady=!0,t.beginPosTable=0,t.dep=[],t.filters=[],t.panelUpdate=!1,t.panelCreate=!1,t.actualUser={},t.newPassword="",t.repeatedNewPassword="",t.init=function(){e.post(n+"/list/departement",t.chosenFilters).then((function(e){t.dep=e.data}),(function(e){t.data=e.data||"Request failed",t.status=e.status}))},t.fetchData=function(){t.chosenFilters={departement:t.filters.departement},e.post(n+"/list",t.chosenFilters).then((function(e){t.status=e.status,t.data=e.data;for(var a=t.data,s=0;s<a.length;s++)a[s].idTemp=s;t.data=a,console.log(t.data)}),(function(e){t.data=e.data||"Request failed",t.status=e.status}))},t.changeUser=function(e){t.panelUpdate=!0,t.panelCreate=!1,t.actualUser=o.copy(t.data[e])},t.updateUser=function(){e.post(n+"/update_user",t.actualUser).then((function(e){t.status=e.status,t.data=e.data;t.data;console.log(e.data),t.Toast.fire({icon:"success",title:"Le compte "+t.actualUser.email+" a été mis à jour."}),t.actualUser={}}),(function(e){t.data=e.data||"Request failed",t.status=e.status,t.Toast.fire({icon:"error",title:"Erreur serveur !."}),t.actualUser={}})),t.fetchData(),t.panelUpdate=!1},t.createUser=function(){e.post(n+"/create_user",t.actualUser).then((function(e){t.status=e.status,t.data=e.data;t.data;console.log(e.data),t.Toast.fire({icon:"success",title:"Compte créé avec succés.",text:"Le compte "+t.actualUser.email+" a été créé. Son mot de passe est "+e.data.pass+".",time:5e3})}),(function(e){t.data=e.data||"Request failed",t.status=e.status,t.Toast.fire({icon:"error",title:"Erreur serveur !."})})),t.panelCreate=!1,t.actualUser={},t.fetchData()},t.checkPassword=function(){return t.newPassword.length>7&&t.newPassword===t.repeatedNewPassword},t.changePassword=function(){e.post(n+"/update_password",{i:t.actualUser.email,p:t.newPassword}).then((function(e){t.status=e.status,t.data=e.data;t.data;console.log(e.data),t.Toast.fire({icon:"success",title:"Le mot du passe du compte "+t.actualUser.email+" a été mis à jour."})}),(function(e){t.data=e.data||"Request failed",t.status=e.status,t.Toast.fire({icon:"error",title:"Erreur serveur !."})})),t.newPassword=null,t.repeatedNewPassword=null,t.fetchData(),t.panelUpdate=!1},t.moveTable=function(e){e?t.beginPosTable+=t.beginPosTable>t.data.length-15?0:15:t.beginPosTable-=t.beginPosTable>14?15:0},t.resetFilters=function(){delete t.mailSearch,t.filters={},t.fetchData()},t.fetchData(),t.init(),t.tableUrl=n+"table/init",t.filterUrl=n+"filter/init",t.headers=[],t.filtersData=[],t.dataReady=!0,t.chosenFilters={},t.filters=[{id:1,lib:"Demande d'avis et consultations",slug:"avis",active:!1},{id:2,lib:"Demande d'autorisations",slug:"autorisation",active:!1},{id:3,lib:"Contrats",slug:"contrat",active:!1},{id:4,lib:"Affaires contentieuses et litiges",slug:"litige",active:!1},{id:5,lib:"Obligations",slug:"obligations",active:!1},{id:6,lib:"Collaborateurs service juridique",slug:"collaborateurs_juridique",active:!1}],t.displayFilters=!1,t.clearFilters=function(){delete t.chosenFilters,t.chosenFilters={}},t.launchFilter=function(e){for(var a=0;a<t.filters.length;a++)t.filters[a].active=!1;t.filters[e].active=!0,t.filterUrl=n+"filter/"+t.filters[e].slug,t.chosenFilters={}},t.reportToMail=function(a){t.chosenFilters.processStr=!1,t.filters.map((function(e){t.chosenFilters.processStr=e.active?e.slug:t.chosenFilters.processStr})),t.chosenFilters.processStr&&(t.chosenFilters.format=a,t.dataReady=!1,e.post(n+"report-to-mail",t.chosenFilters).then((function(e){t.status=e.status,t.data=e.data;t.data;t.dataReady=!0,alert("Mail envoyé")}),(function(e){t.data=e.data||"Request failed",t.status=e.status})))}}])}},t=>{t.O(0,[755,101,294],(()=>{return e=7595,t(t.s=e);var e}));t.O()}]);