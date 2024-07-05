/* WordPress */
import { decodeEntities } from '@wordpress/html-entities';
import { __ } from '@wordpress/i18n';
import { useEffect } from '@wordpress/element';

/* Library */
import classNames from 'classnames';
import { isEmpty, find } from 'lodash';

/* Atrc */
import {
	AtrcHr,
	AtrcWrap,
	AtrcText,
	AtrcButton,
	AtrcLink,
	AtrcImg,
	AtrcWireFrameContentSidebar,
	AtrcIcon,
	AtrcList,
	AtrcLi,
	AtrcSpinner,
	AtrcUseParams,
} from 'atrc';

/* Internal */
import withPreviewPattern from '../../components/pattern-preview/hoc-preview-pattern';
import Pricing from '../../components/pricing';
import PatternsGrid from '../../components/patterns-grid';

/* Local */
const PatternDetails = ({
	onPreviewPatternChange,
	excerpt,
	showAuthor = true,
	target = null,
	showPrice = true,
	pattern,
}) => {
	return (
		<AtrcWireFrameContentSidebar
			wrapProps={{
				tag: 'div',
				allowContainer: true,
				className: 'alignfull',
			}}
			renderContent={
				<>
					{pattern &&
					pattern.featured_images &&
					pattern.featured_images.full &&
					pattern.featured_images.full.url ? (
						<AtrcWrap
							className={classNames(
								'ps-card',
								'at-bdr',
								'at-p',
								'at-bg-cl',
								'at-bg-white',
								'at-h',
								'at-flx',
								'at-al-itm-ctr',
								'at-jfy-cont-ctr',
								'ps__single--feature-img',
								'at-pos'
							)}>
							<AtrcImg
								src={pattern.featured_images.full.url}
								alt={decodeEntities(pattern.title.rendered)}
							/>
							<AtrcButton
								className={classNames('at-pos', 'at-z-idx')}
								variant='info'
								onClick={() => onPreviewPatternChange(pattern)}>
								{__('Live Preview', 'patterns-store')}
							</AtrcButton>
						</AtrcWrap>
					) : null}

					{/* <AtrcWrap
						className={classNames(
							'at-bg-cl',
							'at-bg-white',
							'ps-card',
							'at-bdr',
							'at-p',
							'at-flx',
							'at-jfy-cont-btw',
							'at-m',
							'ps__single-actions'
						)}>
					

						<AtrcWrap
							className={classNames('at-flx', 'at-gap', 'at-al-itm-ctr')}>
							<AtrcText tag='span'>{__('Share', 'patterns-store')}</AtrcText>
							<AtrcWrap
								className={classNames(
									'at-flx',
									'at-al-itm-ctr',
									'at-gap',
									'ps-social-icon'
								)}>
								<AtrcLink target='_blank' href='#'>
									<AtrcIcon
										type='svg'
										svg=' <svg xmlns="http://www.w3.org/2000/svg" class="at-svg" viewBox="0 0 320 512"><path d="M80 299.3V512H196V299.3h86.5l18-97.8H196V166.9c0-51.7 20.3-71.5 72.7-71.5c16.3 0 29.4 .4 37 1.2V7.9C291.4 4 256.4 0 236.2 0C129.3 0 80 50.5 80 159.4v42.1H14v97.8H80z"/></svg>'
									/>
								</AtrcLink>

								<AtrcIcon
									type='svg'
									svg='<svg class="at-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M459.4 151.7c.3 4.5 .3 9.1 .3 13.6 0 138.7-105.6 298.6-298.6 298.6-59.5 0-114.7-17.2-161.1-47.1 8.4 1 16.6 1.3 25.3 1.3 49.1 0 94.2-16.6 130.3-44.8-46.1-1-84.8-31.2-98.1-72.8 6.5 1 13 1.6 19.8 1.6 9.4 0 18.8-1.3 27.6-3.6-48.1-9.7-84.1-52-84.1-103v-1.3c14 7.8 30.2 12.7 47.4 13.3-28.3-18.8-46.8-51-46.8-87.4 0-19.5 5.2-37.4 14.3-53 51.7 63.7 129.3 105.3 216.4 109.8-1.6-7.8-2.6-15.9-2.6-24 0-57.8 46.8-104.9 104.9-104.9 30.2 0 57.5 12.7 76.7 33.1 23.7-4.5 46.5-13.3 66.6-25.3-7.8 24.4-24.4 44.8-46.1 57.8 21.1-2.3 41.6-8.1 60.4-16.2-14.3 20.8-32.2 39.3-52.6 54.3z"/></svg>'
								/>
								<AtrcIcon
									type='svg'
									svg='<svg class="at-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M100.3 448H7.4V148.9h92.9zM53.8 108.1C24.1 108.1 0 83.5 0 53.8a53.8 53.8 0 0 1 107.6 0c0 29.7-24.1 54.3-53.8 54.3zM447.9 448h-92.7V302.4c0-34.7-.7-79.2-48.3-79.2-48.3 0-55.7 37.7-55.7 76.7V448h-92.8V148.9h89.1v40.8h1.3c12.4-23.5 42.7-48.3 87.9-48.3 94 0 111.3 61.9 111.3 142.3V448z"/></svg>'
								/>
								<AtrcIcon
									type='svg'
									svg='<svg class="at-svg" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512"><path d="M224.1 141c-63.6 0-114.9 51.3-114.9 114.9s51.3 114.9 114.9 114.9S339 319.5 339 255.9 287.7 141 224.1 141zm0 189.6c-41.1 0-74.7-33.5-74.7-74.7s33.5-74.7 74.7-74.7 74.7 33.5 74.7 74.7-33.6 74.7-74.7 74.7zm146.4-194.3c0 14.9-12 26.8-26.8 26.8-14.9 0-26.8-12-26.8-26.8s12-26.8 26.8-26.8 26.8 12 26.8 26.8zm76.1 27.2c-1.7-35.9-9.9-67.7-36.2-93.9-26.2-26.2-58-34.4-93.9-36.2-37-2.1-147.9-2.1-184.9 0-35.8 1.7-67.6 9.9-93.9 36.1s-34.4 58-36.2 93.9c-2.1 37-2.1 147.9 0 184.9 1.7 35.9 9.9 67.7 36.2 93.9s58 34.4 93.9 36.2c37 2.1 147.9 2.1 184.9 0 35.9-1.7 67.7-9.9 93.9-36.2 26.2-26.2 34.4-58 36.2-93.9 2.1-37 2.1-147.8 0-184.8zM398.8 388c-7.8 19.6-22.9 34.7-42.6 42.6-29.5 11.7-99.5 9-132.1 9s-102.7 2.6-132.1-9c-19.6-7.8-34.7-22.9-42.6-42.6-11.7-29.5-9-99.5-9-132.1s-2.6-102.7 9-132.1c7.8-19.6 22.9-34.7 42.6-42.6 29.5-11.7 99.5-9 132.1-9s102.7-2.6 132.1 9c19.6 7.8 34.7 22.9 42.6 42.6 11.7 29.5 9 99.5 9 132.1s2.7 102.7-9 132.1z"/></svg>'
								/>
							</AtrcWrap>
						</AtrcWrap>
					</AtrcWrap> */}

					{'pattern-kit' === pattern['product-type'] && pattern.patterns ? (
						<>
							<AtrcText
								tag='h4'
								className={classNames('at-m', 'ps__single--pattern-grid-ttl')}>
								{__('Availabel Patterns', 'patterns-store')}
							</AtrcText>
							<AtrcHr />
							<AtrcWrap className='ps__single--pattern-grid at-m'>
								<PatternsGrid
									patterns={pattern.patterns}
									onPreviewPatternChange={onPreviewPatternChange}
									showThumbnail={true}
									showAuthor={false}
									showPrice={false}
									showTitle={true}
									showSubTitle={false}
									showPreview={false}
									showPurchase={false}
									showCopyOrMessage={false}
								/>
							</AtrcWrap>
						</>
					) : null}

					<AtrcWrap
						className='ps__single--desc at-m'
						dangerouslySetInnerHTML={{
							__html: pattern.excerpt.rendered,
						}}
					/>
				</>
			}
			renderSidebar={
				<AtrcWrap className={classNames('at-flx', 'at-gap', 'at-flx-col')}>
					{showPrice && (
						<AtrcWrap
							className={classNames(
								'ps-card',
								'at-bdr',
								'at-p',
								'at-bg-cl',
								'at-bg-white',
								'ps__single--purchase'
							)}>
							<AtrcText
								tag='h6'
								className={classNames('at-m')}>
								{pattern && pattern.price
									? __('Purchase', 'patterns-store')
									: __('Free', 'patterns-store')}
							</AtrcText>
							<AtrcHr />
							<AtrcWrap
								className={classNames('at-flx', 'at-flx-col', 'at-gap')}>
								{pattern.price ? (
									<AtrcText
										className={classNames('ps-ls-itm-pricing')}
										tag='span'>
										{__('Start From : ', 'patterns-store')}
										{pattern.price}
									</AtrcText>
								) : null}

								<Pricing pattern={pattern} />
							</AtrcWrap>
						</AtrcWrap>
					)}

					{'pattern' === pattern['product-type'] && pattern['pattern-kit'] ? (
						<AtrcWrap
							className={classNames(
								'ps-card',
								'at-bdr',
								'at-p',
								'at-bg-cl',
								'at-bg-white'
							)}>
							<AtrcWrap>
								<AtrcText
									tag='h4'
									className={classNames('at-m', 'ps__single--ttl')}>
									{pattern.title.rendered}
								</AtrcText>
								{pattern['pattern-kit'] && (
									<AtrcWrap
										tag='span'
										className={classNames(
											'at-m',
											'at-flx',
											'at-al-itm-ctr',
											'at-gap',
											'ps__single--ttl-sub'
										)}>
										{__('in', 'patterns-store')}
										<AtrcLink
											target='_blank'
											href={pattern['pattern-kit'].link}>
											{pattern['pattern-kit'].title.rendered}
										</AtrcLink>
									</AtrcWrap>
								)}
							</AtrcWrap>
							<AtrcHr />
							<AtrcButton
								isLink
								variant='outline-light'
								href={pattern['pattern-kit'].link}>
								{__('View pattern kit', 'patterns-store')}
							</AtrcButton>
						</AtrcWrap>
					) : null}

					{showAuthor && pattern.author_data ? (
						<AtrcWrap
							className={classNames(
								'ps-card',
								'at-bdr',
								'at-p',
								'at-bg-cl',
								'at-bg-white'
							)}>
							<AtrcText
								tag='h6'
								className={classNames('at-m')}>
								{__('Author', 'patterns-store')}
							</AtrcText>
							<AtrcHr />

							<AtrcLink
								target='_blank'
								href={pattern.author_data.url}
								className={classNames(
									'ps-auth-avatar',
									'at-flx-srnk-1',
									'at-flx',
									'at-gap',
									'at-al-itm-ctr'
								)}
								target={target}>
								<AtrcImg
									className={classNames(
										'ps-auth-avatar-img',
										'at-flx-srnk-1',
										'at-flx',
										'at-gap',
										'at-w',
										'at-h',
										'at-bdr-rad'
									)}
									alt={pattern.author_data.name}
									src={pattern.author_data.avatar}
								/>
								{pattern.author_data.name}
							</AtrcLink>
						</AtrcWrap>
					) : null}
					{pattern.patterns_store_pattern_tax_terms &&
					pattern.patterns_store_pattern_tax_terms.download_category ? (
						<AtrcWrap
							className={classNames(
								'ps-card',
								'at-bdr',
								'at-p',
								'at-bg-cl',
								'at-bg-white'
							)}>
							<AtrcText
								tag='h6'
								className={classNames('at-m')}>
								{__('Categories', 'patterns-store')}
							</AtrcText>
							<AtrcHr />
							<AtrcWrap className={classNames('at-flx', 'at-gap')}>
								{pattern.patterns_store_pattern_tax_terms.download_category.map(
									(term) => (
										<AtrcButton
											isLink
											variant='outline-primary'
											href={term.link}
											key={term.id}>
											{term.name}
										</AtrcButton>
									)
								)}
							</AtrcWrap>
						</AtrcWrap>
					) : null}
					{pattern.patterns_store_pattern_tax_terms &&
					pattern.patterns_store_pattern_tax_terms.download_tag ? (
						<AtrcWrap
							className={classNames(
								'ps-card',
								'at-bdr',
								'at-p',
								'at-bg-cl',
								'at-bg-white'
							)}>
							<AtrcText
								tag='h6'
								className={classNames('at-m')}>
								{__('Tags', 'patterns-store')}
							</AtrcText>
							<AtrcHr />
							<AtrcWrap className={classNames('at-flx', 'at-gap')}>
								{pattern.patterns_store_pattern_tax_terms.download_tag.map(
									(term) => (
										<AtrcButton
											isLink
											variant='outline-light'
											href={term.link}
											key={term.id}>
											{term.name}
										</AtrcButton>
									)
								)}
							</AtrcWrap>
						</AtrcWrap>
					) : null}

					{pattern.patterns_store_pattern_tax_terms &&
					pattern.patterns_store_pattern_tax_terms
						.patterns_store_pattern_plugin ? (
						<AtrcWrap
							className={classNames(
								'ps-card',
								'at-bdr',
								'at-p',
								'at-bg-cl',
								'at-bg-white'
							)}>
							<AtrcText
								tag='h6'
								className={classNames('at-m')}>
								{__('Required Plugins', 'patterns-store')}
							</AtrcText>
							<AtrcHr />

							<AtrcList
								className={classNames('at-flx', 'at-flx-col', 'at-gap')}>
								{pattern.patterns_store_pattern_tax_terms.patterns_store_pattern_plugin.map(
									(term) => (
										<AtrcLi
											hasIcon
											className={classNames('at-flx', 'at-itm-ctr', 'at-gap')}>
											<AtrcIcon
												type='svg'
												svg='<svg class="at-svg" xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" ><path d="M6 0a.5.5 0 0 1 .5.5V3h3V.5a.5.5 0 0 1 1 0V3h1a.5.5 0 0 1 .5.5v3A3.5 3.5 0 0 1 8.5 10c-.002.434-.01.845-.04 1.22-.041.514-.126 1.003-.317 1.424a2.08 2.08 0 0 1-.97 1.028C6.725 13.9 6.169 14 5.5 14c-.998 0-1.61.33-1.974.718A1.92 1.92 0 0 0 3 16H2c0-.616.232-1.367.797-1.968C3.374 13.42 4.261 13 5.5 13c.581 0 .962-.088 1.218-.219.241-.123.4-.3.514-.55.121-.266.193-.621.23-1.09.027-.34.035-.718.037-1.141A3.5 3.5 0 0 1 4 6.5v-3a.5.5 0 0 1 .5-.5h1V.5A.5.5 0 0 1 6 0M5 4v2.5A2.5 2.5 0 0 0 7.5 9h1A2.5 2.5 0 0 0 11 6.5V4z"/></svg>'
											/>
											<AtrcLink
												target='_blank'
												href={term.link}
												key={term.id}>
												{term.name}
											</AtrcLink>
										</AtrcLi>
									)
								)}
							</AtrcList>
						</AtrcWrap>
					) : null}
				</AtrcWrap>
			}
			contentProps={{
				contentCol: 'at-col-md-8',
			}}
			sidebarProps={{
				sidebarCol: 'at-col-md-4 at-m ps__single--sidebar',
			}}
		/>
	);
};

const PatternWithPreviewPattern = withPreviewPattern(PatternDetails);

const SinglePattern = (props) => {
	const { items, item, getItem, setItem } = props;

	const { id } = AtrcUseParams(),
		itemId = parseInt(id);

	useEffect(() => {
		if (items && (!item || isEmpty(item) || item.id !== itemId)) {
			const singleItem = find(items, (itm) => itm.id === parseInt(id));
			if (!singleItem) {
				getItem(itemId);
			} else {
				setItem(singleItem);
			}
		}
	}, [items]);

	if (!item || !item.id) {
		return <AtrcSpinner />;
	}

	if (item.id !== itemId) {
		return <AtrcWrap>{__('Something went wrong!')}</AtrcWrap>;
	}

	return (
		<PatternWithPreviewPattern
			patterns={[]}
			pattern={item}
		/>
	);
};
export default SinglePattern;
