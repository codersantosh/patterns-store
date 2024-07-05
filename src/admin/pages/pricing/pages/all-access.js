/* WordPress */
import { __ } from '@wordpress/i18n';

import { useContext } from '@wordpress/element';

/* Library */
import classNames from 'classnames';

import { cloneDeep } from 'lodash';

/*Atrc*/
import {
	AtrcSpinner,
	AtrcTitleTemplate2,
	AtrcWireFrameContentSidebar,
	AtrcWireFrameHeaderContentFooter,
	AtrcPrefix,
	AtrcPanelBody,
	AtrcPanelRow,
	AtrcText,
	AtrcControlSelectPost,
	AtrcNestedObjUpdateByKey1,
} from 'atrc';

/* Inbuilt */
import { AtrcReduxContextData } from '../../../routes';
import { DocsTitle } from '../../../components/molecules';

/*Local*/
const MainContent = () => {
	const data = useContext(AtrcReduxContextData);

	const { dbSettings, dbUpdateSetting } = data;

	const { pricing = {} } = dbSettings;
	const { allAccess = 0 } = pricing;

	return (
		<AtrcPanelRow className={classNames('at-m')}>
			<AtrcControlSelectPost
				label={__('Select "all access" product', 'patterns-store')}
				help={__(
					'Any product selected as an "All Access" product is considered special. When a user purchases this product, they gain access to all patterns and pattern kits.',
					'patterns-store'
				)}
				wrapProps={{
					className: 'at-flx-grw-1',
				}}
				value={allAccess}
				onChange={(newVal) => {
					const updatedSettings = AtrcNestedObjUpdateByKey1({
						settings: pricing,
						key1: 'allAccess',
						val1: newVal,
					});
					dbUpdateSetting('pricing', updatedSettings);
				}}
				postType={PatternsStoreLocalize.postType}
			/>
		</AtrcPanelRow>
	);
};

const Documentation = () => {
	const data = useContext(AtrcReduxContextData);

	const { lsSettings, lsSaveSettings } = data;

	return (
		<AtrcWireFrameHeaderContentFooter
			headerRowProps={{
				className: classNames(AtrcPrefix('header-docs'), 'at-m'),
			}}
			renderHeader={
				<DocsTitle
					onClick={() => {
						const localStorageClone = cloneDeep(lsSettings);
						localStorageClone.acDocs1 = !localStorageClone.acDocs1;
						lsSaveSettings(localStorageClone);
					}}
				/>
			}
			renderContent={
				<>
					<AtrcPanelBody
						title={__('What is "All Access" Product?', 'patterns-store')}
						initialOpen={true}>
						<AtrcText
							tag='p'
							className={classNames(AtrcPrefix('m-0'), 'at-m')}>
							{__(
								'A product selected as an "All Access" product is considered special. When a user purchases this product, they gain access to all patterns and pattern kits.',
								'patterns-store'
							)}
						</AtrcText>
					</AtrcPanelBody>
				</>
			}
			allowHeaderRow={false}
			allowHeaderCol={false}
			allowContentRow={false}
			allowContentCol={false}
		/>
	);
};

const Settings = () => {
	const data = useContext(AtrcReduxContextData);
	const { dbIsLoading, dbCanSave, dbSettings, dbSaveSettings, lsSettings } =
		data;

	if (!dbSettings) {
		return null;
	}

	const { acDocs1 } = lsSettings;

	return (
		<AtrcWireFrameHeaderContentFooter
			wrapProps={{
				className: classNames(AtrcPrefix('bg-white'), 'at-bg-cl'),
			}}
			renderHeader={
				<AtrcTitleTemplate2
					title={__('All access', 'patterns-store')}
					btnProps={{
						className: classNames(
							AtrcPrefix('btn-spin'),
							'at-flx',
							'at-al-itm-ctr',
							'at-gap'
						),
						onClick: () => dbSaveSettings(dbSettings),
						isPrimary: true,
						disabled: dbIsLoading || !dbCanSave,
						children: (
							<>
								{dbCanSave
									? __('Save settings', 'patterns-store')
									: __('Saved', 'patterns-store')}
								{dbIsLoading ? <AtrcSpinner variant='inline' /> : ''}
							</>
						),
					}}
				/>
			}
			renderContent={
				<AtrcWireFrameContentSidebar
					wrapProps={{
						allowContainer: true,
						type: 'fluid',
						tag: 'section',
						className: 'at-p',
					}}
					renderContent={<MainContent />}
					renderSidebar={!acDocs1 ? <Documentation /> : null}
					contentProps={{
						contentCol: acDocs1 ? 'at-col-12' : 'at-col-7',
					}}
					sidebarProps={{
						sidebarCol: 'at-col-5',
					}}
				/>
			}
			allowHeaderRow={false}
			allowHeaderCol={false}
			allowContentRow={false}
			allowContentCol={false}
		/>
	);
};
export default Settings;
