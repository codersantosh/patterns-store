/* WordPress */
import { getQueryString, getPathAndQueryString } from '@wordpress/url';

/* Atrc */
import { AtrcPaginationType1 } from 'atrc';

/* Local */
export default function Pagination({
	currentPage = 1,
	onNavigation,
	totalPages,
	param,
	setQueryArgs,
}) {
	if (!totalPages || totalPages <= 1) {
		return null;
	}

	const path = getPathAndQueryString(window.location.href);
	const queryString = getQueryString(path) ? '?' + getQueryString(path) : '';
	const basePath = path.replace(queryString, '').replace(/page\/\d+\/?/, '');

	return (
		<AtrcPaginationType1
			totalPages={totalPages}
			currentPage={parseInt(currentPage) || 1}
			doPagination={(event, page) => {
				setQueryArgs({
					[param]: page,
				});
			}}
		/>
	);
}
