/* WordPress */
import { __ } from '@wordpress/i18n';

import { useContext, useState } from '@wordpress/element';

/* Library */
import classNames from 'classnames';

import { cloneDeep } from 'lodash';

/*Atrc*/
import {
	AtrcSpinner,
	AtrcWrap,
	AtrcTitleTemplate2,
	AtrcWireFrameContentSidebar,
	AtrcWireFrameHeaderContentFooter,
	AtrcPrefix,
	AtrcPanelBody,
	AtrcPanelRow,
	AtrcControlSelect,
	AtrcControlSelectPost,
	AtrcControlToggle,
	AtrcList,
	AtrcLi,
	AtrcText,
	AtrcControlText,
	AtrcModal,
	AtrcHr,
	AtrcButtonGroup,
	AtrcButton,
} from 'atrc';

/* Inbuilt */
import { AtrcReduxContextData } from '../../../routes';
import DocsTitle from '../../../components/molecules/docs-title';

/*Local*/
const ModalPostTypeChange = ({ onChange, value }) => {
	const { isOpen = false, from = '', to = '' } = value;
	if (!isOpen) {
		return null;
	}
	return (
		<AtrcModal
			className={classNames('ps-info-modal')}
			bodyOpenClassName='ps-modal__open'
			onRequestClose={() => onChange(from)}
			title={__(
				'Important information about changing Post Type',
				'patterns-store'
			)}>
			<AtrcList
				type='ol'
				className={classNames('at-flx', 'at-flx-col', 'at-gap')}>
				<AtrcLi className={classNames('at-m')}>
					<AtrcText
						tag='h6'
						className={classNames('at-m')}>
						{__('No Automatic Conversion:', 'patterns-store')}
					</AtrcText>

					<AtrcList
						className={classNames('at-flx', 'at-flx-col', 'at-gap', 'at-m')}>
						<AtrcLi className={classNames('at-m')}>
							<AtrcText tag='span'>
								{__(
									'Changing the post type does not automatically convert the existing post data to the new post type.',
									'patterns-store'
								)}
							</AtrcText>
						</AtrcLi>
						<AtrcLi className={classNames('at-m')}>
							<AtrcText tag='span'>
								{__(
									'The data associated with the existing post type will remain unchanged.',
									'patterns-store'
								)}
							</AtrcText>
						</AtrcLi>
					</AtrcList>
				</AtrcLi>
				<AtrcLi>
					<AtrcText
						tag='h6'
						className={classNames('at-m')}>
						{__('Manual Adjustment Required:', 'patterns-store')}
					</AtrcText>
					<AtrcList
						className={classNames('at-flx', 'at-flx-col', 'at-gap', 'at-m')}>
						<AtrcLi className={classNames('at-m')}>
							<AtrcText tag='span'>
								{__(
									'If there is already data associated with the current post type, you will need to manually change and adjust this data to fit the new post type.',
									'patterns-store'
								)}
							</AtrcText>
						</AtrcLi>
					</AtrcList>
				</AtrcLi>

				<AtrcLi className={classNames('at-m')}>
					<AtrcText
						tag='h6'
						className={classNames('at-m')}>
						{__('Backup Recommendation:', 'patterns-store')}
					</AtrcText>
					<AtrcList
						className={classNames('at-flx', 'at-flx-col', 'at-gap', 'at-m')}>
						<AtrcLi className={classNames('at-m')}>
							<AtrcText tag='span'>
								{__(
									'It is highly recommended to back up your data before making any changes to the post type. This will help prevent data loss or corruption during the transition process.',
									'patterns-store'
								)}
							</AtrcText>
						</AtrcLi>
					</AtrcList>
				</AtrcLi>
			</AtrcList>
			<AtrcWrap className={classNames('ps-info-modal-footer', 'at-m')}>
				<AtrcHr />
				<AtrcText>
					{__(
						'By following these guidelines, you can effectively manage the transition between different post types.',
						'patterns-store'
					)}
				</AtrcText>
				<AtrcButtonGroup>
					<AtrcButton
						onClick={() => onChange(to)}
						variant='primary'>
						{__('Confirm change post type', 'patterns-store')}
					</AtrcButton>
					<AtrcButton
						onClick={() => onChange(from)}
						variant='danger'>
						{__('Cancel', 'patterns-store')}
					</AtrcButton>
				</AtrcButtonGroup>
			</AtrcWrap>
		</AtrcModal>
	);
};
const MainContent = () => {
	const [postTypeChangeNotice, setPostTypeChangeNotice] = useState({
		isOpen: false,
		from: '',
		to: '',
	});

	const data = useContext(AtrcReduxContextData);

	const { dbSettings, dbUpdateSetting } = data;

	const { products = {} } = dbSettings;
	const {
		postType = '',
		patternSlug = '',
		categorySlug = '',
		tagSlug = '',
		pluginSlug = '',
		offKits = false,
		excluded = [],
	} = products;

	const updateSettingKey = (key, val) => {
		const settingCloned = cloneDeep(products);
		settingCloned[key] = val;
		dbUpdateSetting('products', settingCloned);
	};

	const postTypeOptions = [
		{
			value: '',
			label: __('Default', 'patterns-store'),
		},
	];
	if (PatternsStoreLocalize.is_edd_active) {
		postTypeOptions.push({
			value: 'download',
			label: __('Easy Digital Downloads(EDD)', 'patterns-store'),
		});
	}

	return (
		<>
			<AtrcPanelRow className={classNames('at-m')}>
				<AtrcControlSelect
					label={__('Select post type for Pattern', 'patterns-store')}
					options={postTypeOptions}
					help={
						<>
							{__('It is recommended to use ', 'patterns-store')}
							<a
								href='https://wordpress.org/plugins/easy-digital-downloads/'
								target='_blank'
								rel='noopener noreferrer'>
								{__('Easy Digital Downloads (EDD)', 'patterns-store')}
							</a>
							{__(
								' if you have plan to sell Patterns, or choose Default for distributing Free patterns. After activating EDD, you can select Easy Digital Downloads (EDD) in the options.',
								'patterns-store'
							)}
						</>
					}
					wrapProps={{
						className: 'at-flx-grw-1',
					}}
					value={postType}
					onChange={(newVal) => {
						setPostTypeChangeNotice({
							isOpen: true,
							from: postType,
							to: newVal,
						});
					}}
				/>
				<ModalPostTypeChange
					onChange={(newVal) => {
						updateSettingKey('postType', newVal);
						setPostTypeChangeNotice({
							isOpen: false,
						});
					}}
					value={postTypeChangeNotice}
				/>
			</AtrcPanelRow>
			<AtrcPanelRow className={classNames('at-m')}>
				<AtrcControlToggle
					label={__('Disable Pattern Kits', 'patterns-store')}
					help={__(
						'Disabling this option will break the Parent-Child Relationship between Pattern Kits and Patterns. In this mode, every post will be considered as an individual Pattern, and Pattern Kits will not be available.',
						'patterns-store'
					)}
					checked={offKits}
					onChange={() => updateSettingKey('offKits', !offKits)}
				/>
			</AtrcPanelRow>
			<AtrcPanelRow className={classNames('at-m')}>
				<AtrcControlSelectPost
					label={__(
						'Exclude items from patterns and patters kits',
						'patterns-store'
					)}
					wrapProps={{
						className: 'at-flx-grw-1',
					}}
					value={excluded}
					onChange={(newVal) => updateSettingKey('excluded', newVal)}
					postType={postType || PatternsStoreLocalize.postType}
					isMulti={true}
					multiValType='array'
				/>
			</AtrcPanelRow>
			<AtrcPanelRow className={classNames('at-m')}>
				<AtrcControlText
					label={__('Customize patterns slug', 'patterns-store')}
					help={__(
						'Modify the slug of post type for pattern. This change will take effect if not explicitly set by other themes/plugins. Note: You may need to resave permalinks settings after changing it.',
						'patterns-store'
					)}
					value={patternSlug}
					onChange={(newVal) => updateSettingKey('patternSlug', newVal)}
				/>
			</AtrcPanelRow>
			<AtrcPanelRow className={classNames('at-m')}>
				<AtrcControlText
					label={__('Customize pattern category slug', 'patterns-store')}
					help={__(
						'Modify the slug of category slug for pattern. This change will take effect if not explicitly set by other themes/plugins. Note: You may need to resave permalinks settings after changing it.',
						'patterns-store'
					)}
					value={categorySlug}
					onChange={(newVal) => updateSettingKey('categorySlug', newVal)}
				/>
			</AtrcPanelRow>
			<AtrcPanelRow className={classNames('at-m')}>
				<AtrcControlText
					label={__('Customize patterns tag slug', 'patterns-store')}
					help={__(
						'Modify the slug of tag slug for pattern. This change will take effect if not explicitly set by other themes/plugins. Note: You may need to resave permalinks settings after changing it.',
						'patterns-store'
					)}
					value={tagSlug}
					onChange={(newVal) => updateSettingKey('tagSlug', newVal)}
				/>
			</AtrcPanelRow>
			<AtrcPanelRow className={classNames('at-m')}>
				<AtrcControlText
					label={__('Customize patterns plugin slug', 'patterns-store')}
					help={__(
						'Modify the slug of plugin slug for pattern. This change will take effect if not explicitly set by other themes/plugins. Note: You may need to resave permalinks settings after changing it.',
						'patterns-store'
					)}
					value={pluginSlug}
					onChange={(newVal) => updateSettingKey('pluginSlug', newVal)}
				/>
			</AtrcPanelRow>
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
						localStorageClone.gDocs1 = !localStorageClone.gDocs1;
						lsSaveSettings(localStorageClone);
					}}
				/>
			}
			renderContent={
				<>
					<AtrcPanelBody
						className={classNames(AtrcPrefix('m-0'))}
						title={__('What are Patterns?', 'patterns-store')}
						initialOpen={true}>
						<AtrcList>
							<AtrcLi>
								{__(
									'WordPress patterns are reusable groups of blocks that let you quickly add pre-designed layouts to your pages and posts.',
									'patterns-store'
								)}
							</AtrcLi>
							<AtrcLi>
								{__(
									'These pre-designed blocks serve as reusable components and can include a variety of elements such as headers, footers, about us sections, accordions, progress bars, service sections, galleries, call-to-actions, and hero sections etc',
									'patterns-store'
								)}
							</AtrcLi>
							<AtrcLi>
								{__(
									'The purpose of patterns is to simplify the website-building process by providing users with professionally designed and easily customizable elements.',
									'patterns-store'
								)}
							</AtrcLi>
						</AtrcList>
					</AtrcPanelBody>
					<AtrcPanelBody
						title={__('What are Pattern Kits?', 'patterns-store')}
						initialOpen={false}>
						<AtrcList>
							<AtrcLi>
								{__(
									'Pattern kits are collections of similar patterns bundled together.',
									'patterns-store'
								)}
							</AtrcLi>
							<AtrcLi>
								{__(
									'These kits act as a set of related or cohesive design elements that users can apply to their websites collectively.',
									'patterns-store'
								)}
							</AtrcLi>
							<AtrcLi>
								{__(
									'The idea is to offer users a curated collection of patterns that work well together, providing a consistent and aesthetically pleasing design across various sections and pages of a website.',
									'patterns-store'
								)}
							</AtrcLi>
						</AtrcList>
					</AtrcPanelBody>
					<AtrcPanelBody
						title={__(
							'How to setup Pattern Kits and Patterns technically?',
							'patterns-store'
						)}
						initialOpen={false}>
						<AtrcText tag='h4'>
							{__('Establish Parent-Child Relationship', 'patterns-store')}
						</AtrcText>
						<AtrcText tag='p'>
							{__(
								'In the context of your Patterns and Pattern Kits setup, creating a parent-child relationship is crucial for organizing your design elements effectively. Follow these steps to establish this hierarchy:',
								'patterns-store'
							)}
						</AtrcText>
						<AtrcWrap tag='ol'>
							<AtrcLi>
								<AtrcText tag='h4'>
									{__('Creating a New Pattern Kit', 'patterns-store')}
								</AtrcText>
								<AtrcText tag='p'>
									{__(
										'By default, any top-level parent post acts as a "Pattern Kit." When you create a new post without selecting a parent, it is considered a top-level parent and automatically functions as a "Pattern Kit."',
										'patterns-store'
									)}
								</AtrcText>
							</AtrcLi>
							<AtrcLi>
								<AtrcText tag='h4'>
									{__('Adding Patterns to a Kit:', 'patterns-store')}
								</AtrcText>
								<AtrcText tag='p'>
									{__(
										'Once your "Pattern Kit" is created (or any top-level parent post), you can now add individual "Patterns" to it. When creating or editing a "Pattern," locate the "Parent Pattern Kit" dropdown in the editor.',
										'patterns-store'
									)}
								</AtrcText>
							</AtrcLi>
							<AtrcLi>
								<AtrcText tag='h4'>
									{__('Selecting Parent Pattern Kit:', 'patterns-store')}
								</AtrcText>
								<AtrcText tag='p'>
									{__(
										'In the "Parent Pattern Kit" dropdown, choose the appropriate "Pattern Kit" to associate the current "Pattern" with. This establishes a parent-child relationship between the "Pattern Kit" and the individual "Pattern."',
										'patterns-store'
									)}
								</AtrcText>
							</AtrcLi>
							<AtrcLi>
								<AtrcText tag='h4'>
									{__('Direct Children of Pattern Kits:', 'patterns-store')}
								</AtrcText>
								<AtrcText tag='p'>
									{__(
										'All direct children of "Pattern Kits" are considered "Patterns" within the hierarchy. This default behavior allows you to easily distinguish between top-level "Pattern Kits" and their associated "Patterns."',
										'patterns-store'
									)}
								</AtrcText>
							</AtrcLi>
							<AtrcLi>
								<AtrcText tag='h4'>
									{__('Visual Representation:', 'patterns-store')}
								</AtrcText>
								<AtrcText tag='p'>
									{__(
										'In the WordPress admin interface, the hierarchical relationship will be visually represented, showing the nested structure of your "Pattern Kits" and their associated "Patterns."',
										'patterns-store'
									)}
								</AtrcText>
							</AtrcLi>
						</AtrcWrap>
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

	const { gDocs1 } = lsSettings;

	return (
		<AtrcWireFrameHeaderContentFooter
			wrapProps={{
				className: classNames(AtrcPrefix('bg-white'), 'at-bg-cl'),
			}}
			renderHeader={
				<AtrcTitleTemplate2
					title={__('General settings', 'patterns-store')}
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
					renderSidebar={!gDocs1 ? <Documentation /> : null}
					contentProps={{
						contentCol: gDocs1 ? 'at-col-12' : 'at-col-7',
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
