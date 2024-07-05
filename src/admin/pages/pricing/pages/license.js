/* WordPress */
import { __ } from '@wordpress/i18n';
import { useContext } from '@wordpress/element';

/* Library */
import classNames from 'classnames';

import { cloneDeep, map } from 'lodash';

/*Atrc*/
import {
	AtrcText,
	AtrcWireFrameContentSidebar,
	AtrcWireFrameHeaderContentFooter,
	AtrcPrefix,
	AtrcPanelBody,
	AtrcPanelRow,
	AtrcControlText,
	AtrcRepeater,
	AtrcRepeaterGroup,
	AtrcRepeaterGroupAdd,
	AtrcControlToggle,
	AtrcTitleTemplate1,
	AtrcNestedObjAddByKey1,
	AtrcNestedObjAddByKey3,
	AtrcNestedObjDeleteByKey2,
	AtrcNestedObjDeleteByKey4,
	AtrcNestedObjUpdateByKey1,
	AtrcNestedObjUpdateByKey3,
	AtrcNestedObjUpdateByKey4,
} from 'atrc';

/* Inbuilt */
import { AtrcReduxContextData } from '../../../routes';
import { DocsTitle } from '../../../components/molecules';

/*Local*/
const MainContent = () => {
	const data = useContext(AtrcReduxContextData);

	const { dbSettings, dbUpdateSetting } = data;

	const { pricing = {} } = dbSettings;

	const { onLicense = false, license = [] } = pricing;

	return (
		<>
			<AtrcPanelRow className={classNames('at-m')}>
				<AtrcControlToggle
					label={__('Enable item activation limit', 'patterns-store')}
					help={__(
						'Activate a limit on the number of activations for variable pricing and enable license options as well.',
						'patterns-store'
					)}
					checked={onLicense}
					onChange={() => {
						const updatedSettings = AtrcNestedObjUpdateByKey1({
							settings: pricing,
							key1: 'onLicense',
							val1: !onLicense,
						});
						dbUpdateSetting('pricing', updatedSettings);
					}}
				/>
			</AtrcPanelRow>
			{onLicense ? (
				<AtrcPanelRow className={classNames('at-m')}>
					<AtrcRepeater
						label={__('License group', 'patterns-store')}
						groups={() =>
							map(license, (group, groupIndex) => (
								<AtrcRepeaterGroup
									key={groupIndex}
									groupIndex={groupIndex}
									deleteGroup={(itmIndex) => {
										const updatedSettings = AtrcNestedObjDeleteByKey2({
											settings: pricing,
											key1: 'license',
											key2: groupIndex,
										});
										dbUpdateSetting('pricing', updatedSettings);
									}}
									groupTitle={sprintf(
										// translators: %s: placeholder for idx
										__('License group %d', 'patterns-store'),
										groupIndex + 1
									)}
									deleteTitle={__('Remove license group', 'patterns-store')}>
									<AtrcPanelRow className={classNames('at-m')}>
										<AtrcControlText
											label={__('Filter label', 'patterns-store')}
											value={group.filterLabel}
											onChange={(newVal) => {
												const updatedSettings = AtrcNestedObjUpdateByKey3({
													settings: pricing,
													key1: 'license',
													key2: groupIndex,
													key3: 'filterLabel',
													val3: newVal,
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
												const updatedSettings = AtrcNestedObjUpdateByKey3({
													settings: pricing,
													key1: 'license',
													key2: groupIndex,
													key3: 'filterDesc',
													val3: newVal,
												});
												dbUpdateSetting('pricing', updatedSettings);
											}}
										/>
									</AtrcPanelRow>
									<AtrcPanelRow className={classNames('at-m')}>
										<AtrcControlText
											label={__('Activation limit', 'patterns-store')}
											value={group.limit}
											type='number'
											onChange={(newVal) => {
												const updatedSettings = AtrcNestedObjUpdateByKey3({
													settings: pricing,
													key1: 'license',
													key2: groupIndex,
													key3: 'limit',
													val3: newVal,
												});
												dbUpdateSetting('pricing', updatedSettings);
											}}
										/>
									</AtrcPanelRow>
									<AtrcPanelRow className={classNames('at-m')}>
										<AtrcRepeater
											label={__('License descriptions', 'patterns-store')}
											groups={() =>
												map(group.desc, (item, itemIndex) => (
													<AtrcRepeaterGroup
														key={itemIndex}
														groupIndex={itemIndex}
														deleteGroup={(itmIndex) => {
															const updatedSettings = AtrcNestedObjDeleteByKey4(
																{
																	settings: pricing,
																	key1: 'license',
																	key2: groupIndex,
																	key3: 'desc',
																	key4: itemIndex,
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
																		AtrcNestedObjUpdateByKey4({
																			settings: pricing,
																			key1: 'license',
																			key2: groupIndex,
																			key3: 'desc',
																			key4: itemIndex,
																			val4: newVal,
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
														const addedSettings = AtrcNestedObjAddByKey3({
															settings: pricing,
															key1: 'license',
															key2: groupIndex,
															key3: 'desc',
															val3: '',
														});
														dbUpdateSetting('pricing', addedSettings);
													}}
													tooltipText={__('Add item', 'patterns-store')}
													label={__('Add item', 'patterns-store')}
												/>
											)}
										/>
									</AtrcPanelRow>
								</AtrcRepeaterGroup>
							))
						}
						addGroup={() => (
							<AtrcRepeaterGroupAdd
								addGroup={() => {
									const addedSettings = AtrcNestedObjAddByKey1({
										settings: pricing,
										key1: 'license',
										val1: {
											limit: 1,
											desc: [],
										},
									});
									dbUpdateSetting('pricing', addedSettings);
								}}
								tooltipText={__('Add license group', 'patterns-store')}
								label={__('Add license group', 'patterns-store')}
							/>
						)}
					/>
				</AtrcPanelRow>
			) : null}
		</>
	);
};

const Documentation = () => {
	const data = useContext(AtrcReduxContextData);

	const { lsSettings, lsSaveSettings } = data;

	return (
		<AtrcWireFrameHeaderContentFooter
			wrapProps={{
				className: classNames(AtrcPrefix('bg-white'), 'at-bg-cl'),
			}}
			renderHeader={
				<DocsTitle
					onClick={() => {
						const localStorageClone = cloneDeep(lsSettings);
						localStorageClone.lsDocs1 = !localStorageClone.lsDocs1;
						lsSaveSettings(localStorageClone);
					}}
				/>
			}
			renderContent={
				<>
					<AtrcPanelBody
						className={classNames(AtrcPrefix('m-0'))}
						title={__('What do license options do?', 'patterns-store')}
						initialOpen={true}>
						<AtrcText
							tag='p'
							className={classNames(AtrcPrefix('m-0'), 'at-m')}>
							{__(
								'License options allow you to set a limit on the number of activations for variable pricing in EDD. Additionally, you can filter pricing using licenses and display the corresponding license description based on the selected filter.',
								'patterns-store'
							)}
						</AtrcText>
					</AtrcPanelBody>
					<AtrcPanelBody
						title={__('What is a licensed group?', 'patterns-store')}
						initialOpen={false}>
						<AtrcText
							tag='p'
							className={classNames(AtrcPrefix('m-0'), 'at-m')}>
							{__(
								'A license group allows you to categorize licenses by types, such as limits of 1, 5, 25, and multiple sites. The label and description of the filter will be displayed in the pricing popup based on the selected license. The activation limit will restrict users according to their license, and the license description will be shown in the respective pricing column.',
								'patterns-store'
							)}
						</AtrcText>
					</AtrcPanelBody>
					<AtrcPanelBody
						title={__(
							'How does the plugin handle licenses for patterns, pattern kits, and the all-access plan?',
							'patterns-store'
						)}
						initialOpen={false}>
						<AtrcText
							tag='p'
							className={classNames(AtrcPrefix('m-0'), 'at-m')}>
							{__(
								'The plugin will assign the total number of licenses or the maximum license limit if the same user purchases multiple product types that include the same item.',
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

	const { lsDocs1 } = lsSettings;

	return (
		<AtrcWireFrameHeaderContentFooter
			wrapProps={{
				className: classNames(AtrcPrefix('bg-white'), 'at-bg-cl'),
			}}
			renderHeader={
				<AtrcTitleTemplate1 title={__('License settings', 'patterns-store')} />
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
					renderSidebar={!lsDocs1 ? <Documentation /> : null}
					contentProps={{
						contentCol: lsDocs1 ? 'at-col-12' : 'at-col-7',
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
