/* WordPress */
import { __ } from '@wordpress/i18n';
import { useMemo, useContext } from '@wordpress/element';

/* Library */
import classNames from 'classnames';

/*Atrc*/
import {
	AtrcText,
	AtrcWireFrameContentSidebar,
	AtrcWireFrameHeaderContentFooter,
	AtrcPrefix,
	AtrcPanelRow,
	AtrcControlSelectUser,
	AtrcControlTextarea,
	AtrcFooterTemplate1,
	AtrcButtonSaveTemplate1,
	AtrcTitleTemplate1,
	AtrcHr,
	AtrcControlCheckbox,
	AtrcControlDateTimePicker,
	AtrcControlText,
	AtrcDropdown,
	AtrcMenuGroup,
	AtrcMenuItemsChoice,
	AtrcButton,
	AtrcWrapLib,
	AtrcWrap,
} from 'atrc';

/*Inbuilt*/
import { AtrcReduxContextData } from '../../../routes';

/*Local*/
const MainContent = ({ updateItemData, formInput }) => {
	return (
		<AtrcWireFrameContentSidebar
			wrapProps={{
				tag: 'section',
				className: 'at-p',
			}}
			renderContent={
				<>
					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcControlText
							label={__('Site title', 'patterns-store')}
							wrapProps={{
								className: 'at-flx-grw-1',
							}}
							value={formInput.title}
							onChange={(newVal) => updateItemData('title', newVal)}
						/>
					</AtrcPanelRow>
					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcControlText
							label={__('Site URL', 'patterns-store')}
							wrapProps={{
								className: 'at-flx-grw-1',
							}}
							value={formInput.url}
							onChange={(newVal) => updateItemData('url', newVal)}
						/>
					</AtrcPanelRow>
					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcControlTextarea
							label={__('Site notes', 'patterns-store')}
							wrapProps={{
								className: 'at-flx-grw-1',
							}}
							value={formInput.notes}
							onChange={(newVal) => updateItemData('notes', newVal)}
						/>
					</AtrcPanelRow>

					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcControlText
							label={__('Plugin version', 'patterns-store')}
							wrapProps={{
								className: 'at-flx-grw-1',
							}}
							value={formInput.plugin_version}
							onChange={(newVal) => updateItemData('plugin_version', newVal)}
						/>
					</AtrcPanelRow>
				</>
			}
			renderSidebar={
				<>
					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcWrapLib>
							<AtrcControlCheckbox
								label={__(
									'Stick to the top of the sites list',
									'patterns-store'
								)}
								wrapProps={{
									className: 'at-flx-grw-1',
								}}
								checked={formInput.is_sticky}
								onChange={(newVal) =>
									updateItemData('is_sticky', !formInput.is_sticky)
								}
							/>
						</AtrcWrapLib>
					</AtrcPanelRow>
					<AtrcPanelRow className={classNames('at-m', 'at-m-0')}>
						<AtrcHr className={classNames('at-flx-grw-1')} />
					</AtrcPanelRow>
					<AtrcPanelRow className={classNames('at-m', 'at-m-0')}>
						<AtrcText className={classNames('at-m', 'at-m-0')}>
							{__('Date', 'patterns-store')}
						</AtrcText>
						<AtrcControlDateTimePicker
							label=''
							date={formInput.created_at}
							onChange={(newVal) => updateItemData('created_at', newVal)}
						/>
					</AtrcPanelRow>
					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcWrap
							className={classNames(
								'at-flx',
								'at-flx-grw-1',
								'at-jfy-cont-btw',
								'at-al-itm-ctr'
							)}>
							<AtrcText className={classNames('at-m', 'at-m-0')}>
								{__('Status', 'patterns-store')}
							</AtrcText>
							<AtrcDropdown
								popoverProps={{ placement: 'bottom-start' }}
								renderToggle={({ isOpen, onToggle }) => (
									<AtrcButton
										onClick={onToggle}
										hasIcon={true}
										variant='light'>
										{formInput.status || __('Select status', 'patterns-store')}
									</AtrcButton>
								)}
								renderContent={({ onToggle }) => (
									<AtrcMenuGroup label={__('Change Status', 'patterns-store')}>
										<AtrcMenuItemsChoice
											value={formInput.status}
											choices={[
												{
													value: 'active',
													label: __('Active', 'patterns-store'),
													info: __('Activate the site.', 'patterns-store'),
												},
												{
													value: 'inactive',
													label: __('Inactive', 'patterns-store'),
													info: __('Mark as inactive.', 'patterns-store'),
												},
												{
													value: 'denied',
													label: __('Access denied', 'patterns-store'),
													info: __(
														'Access denied to the site.',
														'patterns-store'
													),
												},
												{
													value: 'flag',
													label: __('Flag', 'patterns-store'),
													info: __('Mark as flagged', 'patterns-store'),
												},
											]}
											onSelect={(newVal) => updateItemData('status', newVal)}
										/>
									</AtrcMenuGroup>
								)}
							/>
						</AtrcWrap>
					</AtrcPanelRow>
					<AtrcPanelRow className={classNames('at-m', 'at-m-0')}>
						<AtrcHr className={classNames('at-flx-grw-1')} />
					</AtrcPanelRow>
					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcControlSelectUser
							label={__('Site author', 'patterns-store')}
							wrapProps={{
								className: 'at-flx-grw-1',
							}}
							value={formInput.author}
							onChange={(newVal) => updateItemData('author', newVal)}
						/>
					</AtrcPanelRow>
					<AtrcPanelRow className={classNames('at-m')}>
						<AtrcControlText
							label={__('IP Address', 'patterns-store')}
							readOnly
							wrapProps={{
								className: 'at-flx-grw-1',
							}}
							value={formInput.ip_address}
						/>
					</AtrcPanelRow>
				</>
			}
			contentProps={{
				contentCol: 'at-col-9',
			}}
			sidebarProps={{
				sidebarCol: 'at-col-3',
			}}
		/>
	);
};

/*Local*/
const Form = (props) => {
	const { updateItemData, item, insertItem, updateItem, isLoading, canSave } =
		props;

	const formInput = useMemo(() => {
		if (item) {
			return item;
		}
		return {};
	}, [item]);

	/* local storage */
	const data = useContext(AtrcReduxContextData);
	const { lsSettings } = data;

	const { siteEditDocs1 } = lsSettings;
	return (
		<AtrcWireFrameHeaderContentFooter
			wrapProps={{
				allowContainer: true,
				type: 'fluid',
				className: classNames(AtrcPrefix('bg-white'), 'at-bg-cl'),
			}}
			renderHeader={
				<AtrcTitleTemplate1
					title={
						formInput.id
							? __('Edit custom site', 'patterns-store')
							: __('Add custom site', 'patterns-store')
					}
				/>
			}
			renderContent={
				<>
					<MainContent
						formInput={formInput}
						updateItemData={updateItemData}
					/>
					<AtrcFooterTemplate1>
						<AtrcButtonSaveTemplate1
							isLoading={isLoading}
							canSave={canSave}
							text={{
								saved: __('Saved', 'patterns-store'),
								save: __('Save site', 'patterns-store'),
							}}
							disabled={isLoading || !canSave}
							onClick={() => {
								if (formInput.id) {
									updateItem(formInput);
								} else {
									insertItem(formInput);
								}
							}}
						/>
					</AtrcFooterTemplate1>
				</>
			}
			allowHeaderRow={false}
			allowHeaderCol={false}
			allowContentRow={false}
			allowContentCol={false}
		/>
	);
};
export default Form;
