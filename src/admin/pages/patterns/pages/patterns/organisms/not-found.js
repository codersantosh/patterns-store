/* WordPress */
import { __ } from '@wordpress/i18n';

/* Atrc */
import { Atrc404Template1 } from 'atrc';

/* Local */
function EmptyResult() {
	return (
		<Atrc404Template1
			title={{ children: __('Not Found', 'patterns-store'), className: 'at-m' }}
			description={{
				children: __(
					'We cannot seem to find the page you are looking for',
					'patterns-store'
				),
			}}
			buttons={[
				{
					hasIcon: false,
					icon: '<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M13 0H6a2 2 0 0 0-2 2 2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h7a2 2 0 0 0 2-2 2 2 0 0 0 2-2V2a2 2 0 0 0-2-2m0 13V4a2 2 0 0 0-2-2H5a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1M3 4a1 1 0 0 1 1-1h7a1 1 0 0 1 1 1v10a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1z"/></svg>',
					text: 'Get started',
					url: '#',
					variant: 'primary',
				},
			]}
		/>
	);
}

export default EmptyResult;
