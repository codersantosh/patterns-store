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
	AtrcControlToggle,
} from 'atrc';

/* Inbuilt */
import { AtrcReduxContextData } from '../../../routes';
import DocsTitle from '../../../components/molecules/docs-title';

/*Local*/
const MainContent = () => {
	const data = useContext(AtrcReduxContextData);

	const { dbSettings, dbUpdateSetting } = data;

	const { deleteAll = false } = dbSettings;

	const updateSettingKey = (key, val) => {
		const settingCloned = cloneDeep(products);
		settingCloned[key] = val;
		dbUpdateSetting('products', settingCloned);
	};

	return (
		<AtrcPanelRow>
			<AtrcControlToggle
				label={__(
					'Remove all plugin settings when deactivating.',
					'patterns-store'
				)}
				checked={deleteAll}
				onChange={() => dbUpdateSetting('deleteAll', !deleteAll)}
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
						localStorageClone.aDocs1 = !localStorageClone.aDocs1;
						lsSaveSettings(localStorageClone);
					}}
				/>
			}
			renderContent={
				<AtrcPanelBody
					className={classNames(AtrcPrefix('m-0'))}
					title={__(
						'What does "Remove all plugin settings when deactivating" do?',
						'patterns-store'
					)}
					initialOpen={true}>
					<AtrcText
						tag='p'
						className={classNames(AtrcPrefix('m-0'), 'at-m')}>
						{__(
							'Enabling this option will erase all settings associated with the plugin from the WordPress options table. However, please note that the selected pages and post types will not be deleted.',
							'patterns-store'
						)}
					</AtrcText>
				</AtrcPanelBody>
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

	const { aDocs1 } = lsSettings;

	return (
		<AtrcWireFrameHeaderContentFooter
			wrapProps={{
				className: classNames(AtrcPrefix('bg-white'), 'at-bg-cl'),
			}}
			renderHeader={
				<AtrcTitleTemplate2
					title={__('Advanced settings', 'patterns-store')}
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
					renderSidebar={!aDocs1 ? <Documentation /> : null}
					contentProps={{
						contentCol: aDocs1 ? 'at-col-12' : 'at-col-7',
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
