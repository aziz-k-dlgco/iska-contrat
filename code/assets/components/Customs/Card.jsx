import React from 'react';
import Tooltip from '../Tooltip';

function Card({ title, value, tooltip }) {
	return (
		<div className="flex flex-col col-span-full sm:col-span-6 xl:col-span-3 bg-white shadow-lg rounded-sm border border-slate-200">
			<div className="px-5 py-5">
				<header>
					<h3 className="text-sm font-semibold text-slate-500 mb-1 flex flex-row">
						<span className="text-slate-800">{title}</span>
						{tooltip && (
							<Tooltip bg="dark" className="relative mr-0 ml-3">
								<div className="text-xs text-slate-200 whitespace-nowrap">
									{tooltip}
								</div>
							</Tooltip>
						)}
					</h3>
					<div className="text-2xl font-bold text-slate-800 mb-1">
						{' '}
						{value}{' '}
					</div>
				</header>
			</div>
		</div>
	);
}

export default Card;
