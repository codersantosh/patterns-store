/* WordPress */
import { __ } from '@wordpress/i18n';

import { useContext, useMemo } from '@wordpress/element';

/* Library */
import classNames from 'classnames';

/*Atrc*/
import { AtrcButton, AtrcWrap, AtrcHeaderTemplate1 } from 'atrc';

/* Inbuilt */
import { AtrcReduxContextData } from '../../routes';

/*Local*/
const AdminHeader = () => {
	const data = useContext(AtrcReduxContextData);

	const { lsSettings, lsSaveSettings } = data;

	const primaryNav = useMemo(() => {
		const adminNav = [
			{
				to: '/',
				children: __('Getting started', 'patterns-store'),
				end: true,
			},
			{
				to: '/settings',
				children: __('Settings', 'patterns-store'),
			},
		];
		if (!PatternsStoreLocalize.has_pro) {
			return adminNav;
		}
		return [
			...adminNav,
			{
				to: '/pricing',
				children: __('Pricing', 'patterns-store'),
			},
			{
				to: '/sites',
				children: __('Sites', 'patterns-store'),
			},
		];
	}, [PatternsStoreLocalize.has_pro]);

	const whiteLabel = {
		logo:
			// eslint-disable-next-line no-undef
			PatternsStoreLocalize.PATTERNS_STORE_URL + 'assets/img/logo.png',
	};

	return (
		<AtrcHeaderTemplate1
			isSticky
			logo={{
				src: whiteLabel.logo,
			}}
			primaryNav={{
				navs: primaryNav,
			}}
			floatingSidebar={() => (
				<AtrcWrap className={classNames()}>
					<AtrcButton
						className={classNames()}
						onClick={() => lsSaveSettings(null)}>
						{__(
							'Show all hidden informations, notices and documentations ',
							'patterns-store'
						)}
					</AtrcButton>
				</AtrcWrap>
			)}
		/>
	);
};

export default AdminHeader;
