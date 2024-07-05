/* WordPress */
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';

/* Library */
import classNames from 'classnames';

/* Atrc */
import {
	AtrcWrap,
	AtrcFooter,
	AtrcWireFrameHeaderContentFooter,
	AtrcPrefix,
	AtrcWireFrameSidebarContent,
	AtrcButton,
	AtrcButtonGroup,
} from 'atrc';

/* Inbuilt */
/* HOC */
import withPreviewPattern from '../../components/pattern-preview/hoc-preview-pattern';

/* Components */
import PatternsGrid from '../../components/patterns-grid';

/* Local Molecules */
import CategoriesSelectList from './molecules/categories-select-list';
import PatternSelectControl from './molecules/pattern-select-control';
import PatternSearch from './molecules/search';
import Pagination from './molecules/pagination';

/* Local */
const PatternsGridWithPreview = withPreviewPattern(PatternsGrid);

const PattersHeaderContentFooter = (props) => {
	const {
		patterns,
		categories,
		totalPages,
		countQueryItems,
		queryArgs,
		setQueryArgs,
		isArchive = true,
	} = props;

	return (
		<AtrcWireFrameHeaderContentFooter
			renderHeader={
				<AtrcWrap
					className={classNames(
						'at-flx',
						'at-al-itm-ctr',
						'at-gap',
						'at-flx-md-row',
						'at-flx-col',
						'at-jfy-cont-btw'
					)}>
					{!PatternsStoreLocalize.offKits ? (
						<AtrcButtonGroup>
							<AtrcButton
								variant='light'
								isActive={
									!queryArgs['product-type'] || '' === queryArgs['product-type']
								}
								onClick={() =>
									setQueryArgs({
										'product-type': '',
									})
								}>
								{__('All', 'patterns-store')}
							</AtrcButton>
							{PatternsStoreLocalize.has_pro ? (
								<AtrcButton
									variant='light'
									isActive={'all-access' === queryArgs['product-type']}
									onClick={() =>
										setQueryArgs({
											'product-type': 'all-access',
										})
									}>
									{__('All Access', 'patterns-store')}
								</AtrcButton>
							) : null}

							<AtrcButton
								variant='light'
								isActive={'pattern-kits' === queryArgs['product-type']}
								onClick={() =>
									setQueryArgs({
										'product-type': 'pattern-kits',
									})
								}>
								{__('Pattern Kits', 'patterns-store')}
							</AtrcButton>
							<AtrcButton
								variant='light'
								isActive={'patterns' === queryArgs['product-type']}
								onClick={() =>
									setQueryArgs({
										'product-type': 'patterns',
									})
								}>
								{__('Patterns', 'patterns-store')}
							</AtrcButton>
						</AtrcButtonGroup>
					) : null}

					<AtrcWrap
						className={classNames(
							'at-flx',
							'at-al-itm-ctr',
							'at-gap',
							'at-flx-md-row',
							'at-flx-col'
						)}>
						{PatternsStoreLocalize.has_pro ? (
							<PatternSelectControl
								label={__('Filter by', 'patterns-store')}
								param='type'
								defaultValue=''
								options={[
									{
										label: __('All', 'patterns-store'),
										value: '',
									},
									{
										label: __('Pro', 'patterns-store'),
										value: 'pro',
									},
									{
										label: __('Free', 'patterns-store'),
										value: 'free',
									},
								]}
								setQueryArgs={setQueryArgs}
								value={queryArgs && queryArgs.type}
							/>
						) : null}

						<PatternSearch
							label={__('Search patterns', 'patterns-store')}
							param='search'
							setQueryArgs={setQueryArgs}
							value={queryArgs && queryArgs.search}
						/>
					</AtrcWrap>
				</AtrcWrap>
			}
			renderContent={
				<PatternsGridWithPreview
					patterns={patterns}
					isArchive={isArchive}
				/>
			}
			renderFooter={
				<AtrcFooter
					className={classNames(
						AtrcPrefix('footer-tbl'),
						'at-flx',
						'at-p',
						'at-jfy-cont-end'
					)}>
					{/* pagination */}
					<Pagination
						totalPages={parseInt(totalPages)}
						currentPage={parseInt(queryArgs.page) || 1}
						setQueryArgs={setQueryArgs}
						param='page'
					/>
				</AtrcFooter>
			}
			headerProps={{
				className: 'ps-card ps-hdr-fl at-bg-white at-p at-bdr at-m',
			}}
			allowHeaderRow={false}
			allowHeaderCol={false}
			allowContentRow={false}
			allowContentCol={false}
			allowFooterRow={false}
			allowFooterCol={false}
		/>
	);
};

const Lists = (props) => {
	const {
		patterns,
		categories,
		totalPages,
		countQueryItems,
		queryArgs,
		setQueryArgs,
	} = props;

	const [catOpts, setCatOpt] = useState(false);

	useEffect(() => {
		const newCatOpts = [];
		categories &&
			categories.forEach((cat) => {
				newCatOpts.push({
					label: cat.name,
					value: cat.slug,
				});
			});
		setCatOpt(newCatOpts);
	}, [categories]);

	/* Without categoreis filter */
	if (!categories || !categories.length) {
		return (
			<AtrcWrap className='at-ctnr-fld'>
				<PattersHeaderContentFooter {...props} />
			</AtrcWrap>
		);
	}

	/* with categories filter */
	return (
		<AtrcWireFrameSidebarContent
			wrapProps={{
				tag: 'div',
				allowContainer: true,
				type: 'fluid',
				// className: '',
			}}
			renderContent={<PattersHeaderContentFooter {...props} />}
			renderSidebar={
				<CategoriesSelectList
					options={catOpts}
					setQueryArgs={setQueryArgs}
					value={queryArgs && queryArgs.categories}
					param='categories'
				/>
			}
			contentProps={{
				contentCol: 'at-col-md-8 at-col-lg-9',
			}}
			sidebarProps={{
				sidebarCol: 'at-col-md-4 at-col-lg-3',
			}}
		/>
	);
};

export default withPreviewPattern(Lists);
