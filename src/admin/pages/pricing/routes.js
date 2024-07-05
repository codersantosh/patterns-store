/* WordPress */
import { __ } from '@wordpress/i18n';
import { useContext } from '@wordpress/element';

/*Atrc*/
import {
	AtrcRoute,
	AtrcRoutes,
	AtrcNavigate,
	AtrcNav,
	AtrcWireFrameSidebarContent,
	AtrcFooterTemplate1,
	AtrcButtonSaveTemplate1,
} from 'atrc';

/*Inbuilt*/
import { AllAccess, Template, Plan, License, Recurring } from './pages';
import { AtrcReduxContextData } from '../../routes';

/*Local*/
const ItemRouters = () => {
	const data = useContext(AtrcReduxContextData);
	const { dbIsLoading, dbCanSave, dbSettings, dbSaveSettings } = data;
	return (
		<>
			<AtrcRoutes>
				<AtrcRoute
					index
					exact
					path='all-access/*'
					element={<AllAccess />}
				/>
				<AtrcRoute
					exact
					path='template/*'
					element={<Template />}
				/>
				<AtrcRoute
					exact
					path='plan/*'
					element={<Plan />}
				/>
				<AtrcRoute
					exact
					path='license/*'
					element={<License />}
				/>

				{PatternsStoreLocalize.has_edd_recurring && (
					<AtrcRoute
						exact
						path='recurring/*'
						element={<Recurring />}
					/>
				)}
				<AtrcRoute
					path='/'
					element={
						<AtrcNavigate
							to='all-access'
							replace
						/>
					}
				/>
			</AtrcRoutes>
			<AtrcFooterTemplate1 useDynamicPosition={true}>
				<AtrcButtonSaveTemplate1
					isLoading={dbIsLoading}
					canSave={dbCanSave}
					text={{
						saved: __('Saved', 'patterns-store'),
						save: __('Save settings', 'patterns-store'),
					}}
					disabled={dbIsLoading || !dbCanSave}
					onClick={() => dbSaveSettings(dbSettings)}
				/>
			</AtrcFooterTemplate1>
		</>
	);
};

const InitPricing = () => {
	const pricingNavs = [
		{
			to: 'all-access',
			children: __('All access', 'patterns-store'),
		},
		{
			to: 'template',
			children: __('Template', 'patterns-store'),
		},
		{
			to: 'plan',
			children: __('Plan', 'patterns-store'),
		},
		{
			to: 'license',
			children: __('License', 'patterns-store'),
		},
	];

	if (PatternsStoreLocalize.has_edd_recurring) {
		pricingNavs.push({
			to: 'recurring',
			children: __('Recurring', 'patterns-store'),
		});
	}

	return (
		<AtrcWireFrameSidebarContent
			wrapProps={{
				tag: 'div',
				className: 'at-ctnr-fld at-p',
			}}
			rowProps={{}}
			renderSidebar={
				<AtrcNav
					variant='vertical'
					navs={pricingNavs}
				/>
			}
			renderContent={<ItemRouters />}
			contentProps={{
				tag: 'div',
				contentCol: 'at-col-10',
			}}
			sidebarProps={{
				sidebarCol: 'at-col-2',
			}}
		/>
	);
};

export default InitPricing;
