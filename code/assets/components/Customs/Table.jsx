import React, {useEffect} from "react";

// Data is passed to the table component from data prop
export default function Table() {
    const data = {
        "title": "Liste des contrats",
        "headers": {
            id: {
                title: "ID",
                display: false,
            },
            name: {
                title: "Nom",
                display: true,
            },
            email: {
                title: "Email",
                display: true,
            },
            location: {
                title: "Location",
                display: true,
            },
            orders: {
                title: "Orders",
                display: true,
            },
            lastOrder: {
                title: "Last order",
                display: true,
            },
            spent: {
                title: "Total spent",
                display: true,
            },
            refunds: {
                title: "Refunds",
                display: true,
            },
            fav : {
                title: "Fav",
                display: false,
            }
        },
        "filters": {
            "all": {
                "title": "Toutes les demandes",
                "selected": true,
                "code": "all",
            },
            "pending": {
                "title": "En attente du manager",
                "selected": false,
                "code": "pending",
            },
            "waiting_attribution": {
                "title": "En attente d'attribution",
                "selected": false,
                "code": "waiting_attribution",
            }
        },
        "data": [
            {
                id: '0',
                name: 'Patricia Semklo',
                email: 'patricia.semklo@app.com',
                location: '🇬🇧 London, UK',
                orders: '24',
                lastOrder: '#123567',
                spent: '$2,890.66',
                refunds: '-',
                fav: false
            },
            {
                id: '1',
                name: 'Dominik Lamakani',
                email: 'dominik.lamakani@gmail.com',
                location: '🇩🇪 Dortmund, DE',
                orders: '77',
                lastOrder: '#779912',
                spent: '$14,767.04',
                refunds: '4',
                fav: false
            },
            {
                id: '2',
                
                name: 'Ivan Mesaros',
                email: 'imivanmes@gmail.com',
                location: '🇫🇷 Paris, FR',
                orders: '44',
                lastOrder: '#889924',
                spent: '$4,996.00',
                refunds: '1',
                fav: true
            },
            {
                id: '3',
                
                name: 'Maria Martinez',
                email: 'martinezhome@gmail.com',
                location: '🇮🇹 Bologna, IT',
                orders: '29',
                lastOrder: '#897726',
                spent: '$3,220.66',
                refunds: '2',
                fav: false
            },
            {
                id: '4',
                
                name: 'Vicky Jung',
                email: 'itsvicky@contact.com',
                location: '🇬🇧 London, UK',
                orders: '22',
                lastOrder: '#123567',
                spent: '$2,890.66',
                refunds: '-',
                fav: true
            },
            {
                id: '5',
                
                name: 'Tisho Yanchev',
                email: 'tisho.y@kurlytech.com',
                location: '🇬🇧 London, UK',
                orders: '14',
                lastOrder: '#896644',
                spent: '$1,649.99',
                refunds: '1',
                fav: true
            },
            {
                id: '6',
                
                name: 'James Cameron',
                email: 'james.ceo@james.tech',
                location: '🇫🇷 Marseille, FR',
                orders: '34',
                lastOrder: '#136988',
                spent: '$3,569.87',
                refunds: '2',
                fav: true
            },
            {
                id: '7',
                
                name: 'Haruki Masuno',
                email: 'haruki@supermail.jp',
                location: '🇯🇵 Tokio, JP',
                orders: '112',
                lastOrder: '#442206',
                spent: '$19,246.07',
                refunds: '6',
                fav: false
            },
            {
                id: '8',
                
                name: 'Joe Huang',
                email: 'joehuang@hotmail.com',
                location: '🇨🇳 Shanghai, CN',
                orders: '64',
                lastOrder: '#764321',
                spent: '$12,276.92',
                refunds: '-',
                fav: true
            },
            {
                id: '9',
                
                name: 'Carolyn McNeail',
                email: 'carolynlove@gmail.com',
                location: '🇮🇹 Milan, IT',
                orders: '19',
                lastOrder: '#908764',
                spent: '$1,289.97',
                refunds: '2',
                fav: false
            }
        ]
    };

    const [title, setTitle] = React.useState(data.title);
    const [count, setCount] = React.useState(data.data.length);
    const [headers, setHeaders] = React.useState(data.headers);
    const [displayColumnsSelector, setDisplayColumnsSelector] = React.useState(false);
    const [selectedFilters, setSelectedFilters] = React.useState(data.filters);
    const [displayFiltersSelector, setDisplayFiltersSelector] = React.useState(false);

    const [currentPage, setCurrentPage] = React.useState(1);
    const [itemsPerPage, setItemsPerPage] = React.useState(8);
    const [indexOfLastItem, setIndexOfLastItem] = React.useState(currentPage * itemsPerPage);
    const [indexOfFirstItem, setIndexOfFirstItem] = React.useState(indexOfLastItem - itemsPerPage);
    const [currentItems, setCurrentItems] = React.useState(data.data.slice(indexOfFirstItem, indexOfLastItem));

    const menuColumns = () => {
        setDisplayColumnsSelector(!displayColumnsSelector);
        setDisplayFiltersSelector(false);
    }

    const menuFilters = () => {
        setDisplayFiltersSelector(!displayFiltersSelector);
        setDisplayColumnsSelector(false);
    }

    const changeColumnDisplay = (index) => {
        let newHeaders = {...headers};
        newHeaders[index].display = !newHeaders[index].display;
        setHeaders(newHeaders);
    }

    const nextPage = () => {
        if (indexOfLastItem < data.data.length) {
            setCurrentPage(currentPage + 1);
            setIndexOfLastItem(indexOfLastItem + itemsPerPage);
            setIndexOfFirstItem(indexOfFirstItem + itemsPerPage);
        }
    }

    const prevPage = () => {
        if (indexOfFirstItem > 0) {
            setCurrentPage(currentPage - 1);
            setIndexOfLastItem(indexOfLastItem - itemsPerPage);
            setIndexOfFirstItem(indexOfFirstItem - itemsPerPage);
        }
    }

    useEffect(() => {
            setCurrentItems(data.data.slice(indexOfFirstItem, indexOfLastItem));
        }
    , [indexOfLastItem, indexOfFirstItem]);

    return (
        <div className="bg-white shadow-lg rounded-sm border border-slate-200 relative">
            <header className="px-5 py-4">
                <h2 className="font-semibold text-slate-800">{ title } <span className="text-slate-400 font-medium">{count}</span></h2>
                {/* Dropdown  checkboxes representing the table filters and columns, use checkboxes */}
                <div className="absolute top-0 right-0 mt-3 mr-3 flex flex-row gap-5">
                    <div className="relative inline-block text-left">
                        <div>
                            <button
                                type="button"
                                className="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="options-menu" aria-expanded="true" aria-haspopup="true"
                                onClick={menuFilters} >
                                Filtres
                                <svg className="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fillRule="evenodd" d="M5 4a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm0 5a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm0 5a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 011-1z" clipRule="evenodd" />
                                </svg>
                            </button>
                        </div>
                    </div>
                    {displayFiltersSelector && (
                        <div className="origin-top-right absolute right-5 mt-5 w-90 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                            <div className="py-1" role="none">
                                {(Object.values(selectedFilters)).map((filter, index) => (
                                    <div key={index} className="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                        <input type="checkbox" className="mr-2" checked={filter.selected} onClick={
                                            () => {
                                                let newFilters = {...selectedFilters};
                                                newFilters[index].selected = !newFilters[index].selected;
                                                setSelectedFilters(newFilters);
                                            }
                                        }/>
                                        {filter.title}
                                    </div>
                                ))}
                            </div>
                        </div>
                    )}

                    <div className="relative inline-block text-left">
                        <div>
                            <button
                                type="button"
                                className="inline-flex justify-center w-full rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" id="options-menu" aria-expanded="true" aria-haspopup="true"
                                onClick={menuColumns}>
                                Colonnes
                                <svg className="-mr-1 ml-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fillRule="evenodd" d="M5 4a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm0 5a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 01-1-1zm0 5a1 1 0 011-1h8a1 1 0 110 2H6a1 1 0 011-1z" clipRule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        {displayColumnsSelector && (
                            <div className="origin-top-right absolute right-0 mt-2 w-90 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 focus:outline-none" role="menu" aria-orientation="vertical" aria-labelledby="options-menu">
                                <div className="py-1" role="none">
                                    {(Object.values(headers)).map((header, index) => (
                                        <div key={index} className="flex items-center px-4 py-2 text-sm text-gray-700 hover:bg-gray-100 hover:text-gray-900" role="menuitem">
                                            <input type="checkbox" className="mr-2"
                                                   checked={header.display}
                                                   onClick={() => { changeColumnDisplay(
                                                       Object.keys(headers)[index],
                                                   )}}
                                            />
                                            {header.title}
                                        </div>
                                    ))}
                                </div>
                            </div>
                        )}

                    </div>

                </div>
            </header>
            <div>
                <div className="overflow-x-auto">
                    <table className="table-auto w-full">
                        {/* Table header */}
                        <thead className="text-xs font-semibold uppercase text-slate-500 bg-slate-50 border-t border-b border-slate-200">
                        <tr>
                            {Object.keys(headers).map((key, index) => {
                                return headers[key].display ?
                                    <th className="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap" key={index}>
                                        {headers[key].title}
                                    </th> : null;
                            })}
                        </tr>
                        </thead>
                        {/* Table body */}
                        <tbody className="text-sm divide-y divide-slate-200">
                        {currentItems.map((row, index) => {
                            return (
                                <tr key={index}>
                                    {Object.keys(headers).map((key, index) => {
                                        return headers[key].display ?
                                            <td className="px-2 first:pl-5 last:pr-5 py-3 whitespace-nowrap w-px" key={index}>
                                                {row[key]}
                                            </td> : null;
                                    })}
                                </tr>
                            )
                        })}
                        </tbody>
                    </table>

                </div>
                <div className="px-6 py-8 border border-slate-200 rounded-sm">
                    <div className="flex flex-col sm:flex-row sm:items-center sm:justify-between">
                        <nav className="mb-4 sm:mb-0 sm:order-1" role="navigation" aria-label="Navigation">
                            <ul className="flex justify-center">
                                <li className="ml-3 first:ml-0"><a
                                    className={`relative inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 ${currentPage === 1 ? 'cursor-not-allowed' : 'text-indigo-500 hover:text-indigo-600'}`}
                                    onClick={prevPage}
                                    >&lt;- Précédent</a></li>
                                <li className="ml-3 first:ml-0"><a
                                    className={`relative inline-flex items-center px-4 py-2 border border-slate-300 text-sm font-medium rounded-md text-slate-700 bg-white hover:bg-slate-50 ${currentPage === Math.ceil(count / itemsPerPage) ? 'cursor-not-allowed' : 'text-indigo-500 hover:text-indigo-600'}`}
                                    onClick={nextPage}
                                    >Suivant -&gt;</a></li>
                            </ul>
                        </nav>
                        <div className="text-sm text-slate-500 text-center sm:text-left">
                            Affichage de &nbsp;
                            <span
                            className="font-medium text-slate-600">{indexOfFirstItem + 1}
                        </span> sur <span
                            className="font-medium text-slate-600">
                            {indexOfLastItem > count ? count : indexOfLastItem}
                        </span> de <span
                            className="font-medium text-slate-600">
                            {count}
                        </span> résultats
                        </div>
                    </div>
                </div>
            </div>
        </div>
    );
}
