/* WordPress */
import { __ } from '@wordpress/i18n';
import { useContext } from '@wordpress/element';

/* Library */
import classNames from 'classnames';

import { cloneDeep, map } from 'lodash';

/*Atrc*/
import {
	AtrcText,
	AtrcSpinner,
	AtrcTitleTemplate2,
	AtrcWireFrameContentSidebar,
	AtrcWireFrameHeaderContentFooter,
	AtrcPrefix,
	AtrcPanelBody,
	AtrcPanelRow,
	AtrcControlText,
	AtrcControlSelect,
	AtrcRepeater,
	AtrcRepeaterGroupAdd,
	AtrcRepeaterGroup,
	AtrcNestedObjAddByKey4,
	AtrcNestedObjDeleteByKey5,
	AtrcNestedObjUpdateByKey2,
	AtrcNestedObjUpdateByKey4,
	AtrcNestedObjUpdateByKey5,
} from 'atrc';

/* Inbuilt */
import { AtrcReduxContextData } from '../../../routes';
import { DocsTitle } from '../../../components/molecules';

/*Local*/
const getLabelByRedurrindId = (id) => {
	if ('day' === id) {
		return __('Daily', 'patterns-store');
	}
	if ('week' === id) {
		return __('Weekly', 'patterns-store');
	}
	if ('month' === id) {
		return __('Monthly', 'patterns-store');
	}
	if ('quarter' === id) {
		return __('Quarterly', 'patterns-store');
	}
	if ('semi-year' === id) {
		return __('Semi-Yearly', 'patterns-store');
	}
	if ('year' === id) {
		return __('Yearly', 'patterns-store');
	}
	if ('lifetime' === id) {
		return __('Lifetime', 'patterns-store');
	}
	return null;
};

const MainContent = () => {
	const data = useContext(AtrcReduxContextData);

	const { dbSettings, dbUpdateSetting } = data;

	const { pricing = {} } = dbSettings;

	const { license = [], recurring = {} } = pricing;

	return (
		<>
			<AtrcPanelRow className={classNames('at-m')}>
				<AtrcControlSelect
					label={__('Recurring tabs', 'patterns-store')}
					wrapProps={{
						className: 'at-flx-grw-1',
					}}
					value={recurring.tabs || []}
					isMulti={true}
					multiValType='array'
					options={[
						{
							value: 'day',
							label: __('Daily', 'patterns-store'),
						},
						{
							value: 'week',
							label: __('Weekly', 'patterns-store'),
						},
						{
							value: 'month',
							label: __('Monthly', 'patterns-store'),
						},
						{
							value: 'quarter',
							label: __('Quarterly', 'patterns-store'),
						},
						{
							value: 'semi-year',
							label: __('Semi-Yearly', 'patterns-store'),
						},
						{
							value: 'year',
							label: __('Yearly', 'patterns-store'),
						},
						{
							value: 'lifetime',
							label: __('Lifetime', 'patterns-store'),
						},
					]}
					onChange={(newVal) => {
						const updatedSettings = AtrcNestedObjUpdateByKey2({
							settings: pricing,
							key1: 'recurring',
							key2: 'tabs',
							val2: newVal,
						});
						const newTabs = updatedSettings.recurring.tabs;
						const clonedDetails = cloneDeep(updatedSettings.recurring.details);
						const newDetails = [];
						if (newTabs) {
							newTabs.forEach((newTab) => {
								const existingDetail = clonedDetails.find(
									(obj) => obj.id === newTab
								);
								if (existingDetail) {
									newDetails.push(existingDetail);
								} else {
									newDetails.push({
										label: '',
										id: newTab,
										desc: [],
									});
								}
							});
						}
						updatedSettings.recurring.details = newDetails;
						dbUpdateSetting('pricing', updatedSettings);
					}}
				/>
			</AtrcPanelRow>

			{recurring.details &&
				map(recurring.details, (group, groupIndex) => {
					const panelTitle = getLabelByRedurrindId(group.id);

					if (panelTitle) {
						return (
							<AtrcPanelRow className={classNames('at-m')}>
								<AtrcPanelBody
									className={classNames('at-flx-grw-1')}
									title={panelTitle}
									initialOpen={false}>
									<AtrcPanelRow className={classNames('at-m')}>
										<AtrcControlText
											label={__('Filter label', 'patterns-store')}
											value={group.filterLabel}
											onChange={(newVal) => {
												const updatedSettings = AtrcNestedObjUpdateByKey4({
													settings: pricing,
													key1: 'recurring',
													key2: 'details',
													key3: groupIndex,
													key4: 'filterLabel',
													val4: newVal,
												});
												dbUpdateSetting('pricing', updatedSettings);
											}}
										/>
									</AtrcPanelRow>
									<AtrcPanelRow className={classNames('at-m')}>
										<AtrcControlText
											label={__('Filter description', 'patterns-store')}
											value={group.filterDesc}
											onChange={(newVal) => {
												const updatedSettings = AtrcNestedObjUpdateByKey4({
													settings: pricing,
													key1: 'recurring',
													key2: 'details',
													key3: groupIndex,
													key4: 'filterDesc',
													val4: newVal,
												});
												dbUpdateSetting('pricing', updatedSettings);
											}}
										/>
									</AtrcPanelRow>
									<AtrcPanelRow className={classNames('at-m')}>
										<AtrcRepeater
											label={__('Recurring descriptions', 'patterns-store')}
											groups={() =>
												map(group.desc, (item, itemIndex) => (
													<AtrcRepeaterGroup
														key={itemIndex}
														groupIndex={itemIndex}
														deleteGroup={(itmIndex) => {
															const updatedSettings = AtrcNestedObjDeleteByKey5(
																{
																	settings: pricing,
																	key1: 'recurring',
																	key2: 'details',
																	key3: groupIndex,
																	key4: 'desc',
																	key5: itemIndex,
																}
															);
															dbUpdateSetting('pricing', updatedSettings);
														}}
														groupTitle={sprintf(
															// translators: %s: placeholder for idx
															__('Item %d', 'patterns-store'),
															itemIndex + 1
														)}
														deleteTitle={__('Remove item', 'patterns-store')}>
														<AtrcPanelRow className={classNames('at-m')}>
															<AtrcControlText
																label={__('Item description', 'patterns-store')}
																value={item}
																onChange={(newVal) => {
																	const updatedSettings =
																		AtrcNestedObjUpdateByKey5({
																			settings: pricing,
																			key1: 'recurring',
																			key2: 'details',
																			key3: groupIndex,
																			key4: 'desc',
																			key5: itemIndex,
																			val5: newVal,
																		});
																	dbUpdateSetting('pricing', updatedSettings);
																}}
															/>
														</AtrcPanelRow>
													</AtrcRepeaterGroup>
												))
											}
											addGroup={() => (
												<AtrcRepeaterGroupAdd
													addGroup={() => {
														const addedSettings = AtrcNestedObjAddByKey4({
															settings: pricing,
															key1: 'recurring',
															key2: 'details',
															key3: groupIndex,
															key4: 'desc',
															val4: '',
														});
														dbUpdateSetting('pricing', addedSettings);
													}}
													tooltipText={__('Add item', 'patterns-store')}
													label={__('Add item', 'patterns-store')}
												/>
											)}
										/>
									</AtrcPanelRow>
								</AtrcPanelBody>
							</AtrcPanelRow>
						);
					}
					return null;
				})}
		</>
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
						localStorageClone.rcDocs1 = !localStorageClone.rcDocs1;
						lsSaveSettings(localStorageClone);
					}}
				/>
			}
			renderContent={
				<>
					<AtrcPanelBody
						className={classNames(AtrcPrefix('m-0'))}
						title={__('What do recurring options do?', 'patterns-store')}
						initialOpen={true}>
						<AtrcText
							tag='p'
							className={classNames(AtrcPrefix('m-0'), 'at-m')}>
							{__(
								'Recurring options allow user to filter pricing using recurring payment plans. They also display the corresponding recurring description based on the selected filter, helping users understand the details and benefits of the recurring payment option they choose. Note that the EDD Recurring plugin is needed for these options to work.',
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

	const { rcDocs1 } = lsSettings;

	return (
		<AtrcWireFrameHeaderContentFooter
			wrapProps={{
				className: classNames(AtrcPrefix('bg-white'), 'at-bg-cl'),
			}}
			renderHeader={
				<AtrcTitleTemplate2
					title={__('Recurring settings', 'patterns-store')}
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
					renderSidebar={!rcDocs1 ? <Documentation /> : null}
					contentProps={{
						contentCol: rcDocs1 ? 'at-col-12' : 'at-col-7',
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
