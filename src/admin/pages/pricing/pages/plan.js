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
	AtrcTitleTemplate1,
	AtrcNestedObjAddByKey2,
	AtrcNestedObjDeleteByKey3,
	AtrcNestedObjUpdateByKey2,
	AtrcNestedObjUpdateByKey3,
	AtrcWrap,
	AtrcLi,
} from 'atrc';

/* Inbuilt */
import { AtrcReduxContextData } from '../../../routes';
import { DocsTitle } from '../../../components/molecules';

/*Local*/
const MainContent = () => {
	const data = useContext(AtrcReduxContextData);

	const { dbSettings, dbUpdateSetting } = data;

	const { pricing = {}, products = {} } = dbSettings;

	const {
		patternPlan = {},
		patternKitPlan = {},
		allAccess = 0,
		allAccessPlan = {},
	} = pricing;
	const { offKits = false } = products;

	return (
		<>
			<AtrcPanelBody
				title={__('Pattern plan', 'patterns-store')}
				initialOpen={false}>
				<AtrcPanelRow className={classNames('at-m')}>
					<AtrcControlText
						label={__('Filter label', 'patterns-store')}
						value={patternPlan.filterLabel}
						onChange={(newVal) => {
							const updatedSettings = AtrcNestedObjUpdateByKey2({
								settings: pricing,
								key1: 'patternPlan',
								key2: 'filterLabel',
								val2: newVal,
							});
							dbUpdateSetting('pricing', updatedSettings);
						}}
					/>
				</AtrcPanelRow>
				<AtrcPanelRow className={classNames('at-m')}>
					<AtrcControlText
						label={__('Filter description', 'patterns-store')}
						value={patternPlan.filterDesc}
						onChange={(newVal) => {
							const updatedSettings = AtrcNestedObjUpdateByKey2({
								settings: pricing,
								key1: 'patternPlan',
								key2: 'filterDesc',
								val2: newVal,
							});
							dbUpdateSetting('pricing', updatedSettings);
						}}
					/>
				</AtrcPanelRow>
				<AtrcPanelRow className={classNames('at-m')}>
					<AtrcControlText
						label={__('Title', 'patterns-store')}
						value={patternPlan.title}
						onChange={(newVal) => {
							const updatedSettings = AtrcNestedObjUpdateByKey2({
								settings: pricing,
								key1: 'patternPlan',
								key2: 'title',
								val2: newVal,
							});
							dbUpdateSetting('pricing', updatedSettings);
						}}
					/>
				</AtrcPanelRow>
				<AtrcPanelRow className={classNames('at-m')}>
					<AtrcControlText
						label={__('Subtitle', 'patterns-store')}
						value={patternPlan.subtitle}
						onChange={(newVal) => {
							const updatedSettings = AtrcNestedObjUpdateByKey2({
								settings: pricing,
								key1: 'patternPlan',
								key2: 'subtitle',
								val2: newVal,
							});
							dbUpdateSetting('pricing', updatedSettings);
						}}
					/>
				</AtrcPanelRow>
				<AtrcPanelRow className={classNames('at-m')}>
					<AtrcRepeater
						label={__('Descriptions', 'patterns-store')}
						groups={() =>
							map(patternPlan.desc, (item, itemIndex) => (
								<AtrcRepeaterGroup
									key={itemIndex}
									groupIndex={itemIndex}
									deleteGroup={(itmIndex) => {
										const updatedSettings = AtrcNestedObjDeleteByKey3({
											settings: pricing,
											key1: 'patternPlan',
											key2: 'desc',
											key3: itemIndex,
										});
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
												const updatedSettings = AtrcNestedObjUpdateByKey3({
													settings: pricing,
													key1: 'patternPlan',
													key2: 'desc',
													key3: itemIndex,
													val3: newVal,
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
									const addedSettings = AtrcNestedObjAddByKey2({
										settings: pricing,
										key1: 'patternPlan',
										key2: 'desc',
										val2: '',
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

			{!offKits ? (
				<AtrcPanelBody
					title={__('Pattern kit plan', 'patterns-store')}
					initialOpen={false}>
					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcControlText
							label={__('Filter label', 'patterns-store')}
							value={patternKitPlan.filterLabel}
							onChange={(newVal) => {
								const updatedSettings = AtrcNestedObjUpdateByKey2({
									settings: pricing,
									key1: 'patternKitPlan',
									key2: 'filterLabel',
									val2: newVal,
								});
								dbUpdateSetting('pricing', updatedSettings);
							}}
						/>
					</AtrcPanelRow>
					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcControlText
							label={__('Filter description', 'patterns-store')}
							value={patternKitPlan.filterDesc}
							onChange={(newVal) => {
								const updatedSettings = AtrcNestedObjUpdateByKey2({
									settings: pricing,
									key1: 'patternKitPlan',
									key2: 'filterDesc',
									val2: newVal,
								});
								dbUpdateSetting('pricing', updatedSettings);
							}}
						/>
					</AtrcPanelRow>
					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcControlText
							label={__('Title', 'patterns-store')}
							value={patternKitPlan.title}
							onChange={(newVal) => {
								const updatedSettings = AtrcNestedObjUpdateByKey2({
									settings: pricing,
									key1: 'patternKitPlan',
									key2: 'title',
									val2: newVal,
								});
								dbUpdateSetting('pricing', updatedSettings);
							}}
						/>
					</AtrcPanelRow>
					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcControlText
							label={__('Subtitle', 'patterns-store')}
							value={patternKitPlan.subtitle}
							onChange={(newVal) => {
								const updatedSettings = AtrcNestedObjUpdateByKey2({
									settings: pricing,
									key1: 'patternKitPlan',
									key2: 'subtitle',
									val2: newVal,
								});
								dbUpdateSetting('pricing', updatedSettings);
							}}
						/>
					</AtrcPanelRow>
					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcRepeater
							label={__('Descriptions', 'patterns-store')}
							groups={() =>
								map(patternKitPlan.desc, (item, itemIndex) => (
									<AtrcRepeaterGroup
										key={itemIndex}
										groupIndex={itemIndex}
										deleteGroup={(itmIndex) => {
											const updatedSettings = AtrcNestedObjDeleteByKey3({
												settings: pricing,
												key1: 'patternKitPlan',
												key2: 'desc',
												key3: itemIndex,
											});
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
													const updatedSettings = AtrcNestedObjUpdateByKey3({
														settings: pricing,
														key1: 'patternKitPlan',
														key2: 'desc',
														key3: itemIndex,
														val3: newVal,
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
										const addedSettings = AtrcNestedObjAddByKey2({
											settings: pricing,
											key1: 'patternKitPlan',
											key2: 'desc',
											val2: '',
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
			) : null}

			{allAccess ? (
				<AtrcPanelBody
					title={__('All access plan', 'patterns-store')}
					initialOpen={false}>
					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcControlText
							label={__('Filter label', 'patterns-store')}
							value={allAccessPlan.filterLabel}
							onChange={(newVal) => {
								const updatedSettings = AtrcNestedObjUpdateByKey2({
									settings: pricing,
									key1: 'allAccessPlan',
									key2: 'filterLabel',
									val2: newVal,
								});
								dbUpdateSetting('pricing', updatedSettings);
							}}
						/>
					</AtrcPanelRow>
					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcControlText
							label={__('Filter description', 'patterns-store')}
							value={allAccessPlan.filterDesc}
							onChange={(newVal) => {
								const updatedSettings = AtrcNestedObjUpdateByKey2({
									settings: pricing,
									key1: 'allAccessPlan',
									key2: 'filterDesc',
									val2: newVal,
								});
								dbUpdateSetting('pricing', updatedSettings);
							}}
						/>
					</AtrcPanelRow>
					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcControlText
							label={__('Title', 'patterns-store')}
							value={allAccessPlan.title}
							onChange={(newVal) => {
								const updatedSettings = AtrcNestedObjUpdateByKey2({
									settings: pricing,
									key1: 'allAccessPlan',
									key2: 'title',
									val2: newVal,
								});
								dbUpdateSetting('pricing', updatedSettings);
							}}
						/>
					</AtrcPanelRow>
					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcControlText
							label={__('Subtitle', 'patterns-store')}
							value={allAccessPlan.subtitle}
							onChange={(newVal) => {
								const updatedSettings = AtrcNestedObjUpdateByKey2({
									settings: pricing,
									key1: 'allAccessPlan',
									key2: 'subtitle',
									val2: newVal,
								});
								dbUpdateSetting('pricing', updatedSettings);
							}}
						/>
					</AtrcPanelRow>
					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcRepeater
							label={__('Descriptions', 'patterns-store')}
							groups={() =>
								map(allAccessPlan.desc, (item, itemIndex) => (
									<AtrcRepeaterGroup
										key={itemIndex}
										groupIndex={itemIndex}
										deleteGroup={(itmIndex) => {
											const updatedSettings = AtrcNestedObjDeleteByKey3({
												settings: pricing,
												key1: 'allAccessPlan',
												key2: 'desc',
												key3: itemIndex,
											});
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
													const updatedSettings = AtrcNestedObjUpdateByKey3({
														settings: pricing,
														key1: 'allAccessPlan',
														key2: 'desc',
														key3: itemIndex,
														val3: newVal,
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
										const addedSettings = AtrcNestedObjAddByKey2({
											settings: pricing,
											key1: 'allAccessPlan',
											key2: 'desc',
											val2: '',
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
			) : null}
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
						localStorageClone.plDocs1 = !localStorageClone.plDocs1;
						lsSaveSettings(localStorageClone);
					}}
				/>
			}
			renderContent={
				<AtrcPanelBody
					title={__('What are the pricing plans?', 'patterns-store')}
					initialOpen={false}>
					<AtrcText tag='p'>
						{__(
							'Pricing plans refer to the various options available for purchasing patterns, pattern kits, and the all-access plan.',
							'patterns-store'
						)}
					</AtrcText>

					<AtrcWrap tag='ol'>
						<AtrcLi>
							<AtrcText tag='h4'>
								{__('Individual Patterns:', 'patterns-store')}
							</AtrcText>
							<AtrcText tag='p'>
								{__(
									'You can purchase individual patterns, which are reusable groups of blocks designed for specific sections like headers, footers, galleries, and more. These patterns simplify the website-building process by providing pre-designed, customizable elements.',
									'patterns-store'
								)}
							</AtrcText>
						</AtrcLi>
						<AtrcLi>
							<AtrcText tag='h4'>
								{__('Pattern Kits:', 'patterns-store')}
							</AtrcText>
							<AtrcText tag='p'>
								{__(
									'These are collections of related patterns bundled together. Pattern kits offer a cohesive set of design elements that can be applied collectively to ensure a consistent and aesthetically pleasing design across different sections and pages of your website.',
									'patterns-store'
								)}
							</AtrcText>
						</AtrcLi>
						<AtrcLi>
							<AtrcText tag='h4'>
								{__('All Access Plan:', 'patterns-store')}
							</AtrcText>
							<AtrcText tag='p'>
								{__(
									'This special plan provides complete access to all available patterns and pattern kits. When a user purchases the all-access plan, they can utilize any pattern or pattern kit without additional purchases. This plan is ideal for users who want a comprehensive set of design tools for their websites.',
									'patterns-store'
								)}
							</AtrcText>
						</AtrcLi>
					</AtrcWrap>
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

	const { plDocs1 } = lsSettings;

	return (
		<AtrcWireFrameHeaderContentFooter
			wrapProps={{
				className: classNames(AtrcPrefix('bg-white'), 'at-bg-cl'),
			}}
			renderHeader={
				<AtrcTitleTemplate1 title={__('Plan settings', 'patterns-store')} />
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
					renderSidebar={!plDocs1 ? <Documentation /> : null}
					contentProps={{
						contentCol: plDocs1 ? 'at-col-12' : 'at-col-7',
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
