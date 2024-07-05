/* WordPress */
import { decodeEntities } from '@wordpress/html-entities';

/* Library */

/* Atrc */
import { AtrcImg, AtrcLink, AtrcText, AtrcWrap } from 'atrc';

/* Inbuilt */
import PatternCopyOrMessage from '../pattern-copy-or-message';

/* Local */
function PatternThumbnail(props) {
	const {
		pattern,
		showPrice = true,
		showThumbnail = true,
		showGuide = true,
		showCopyOrMessage = true,
		target = '_blank',
	} = props;

	const style = {
		display: 'block',
	};

	return (
		<AtrcWrap className='ps-ls-itm-preview at-pos at-z-idx'>
			{showThumbnail && (
				<AtrcLink
					href={pattern.link}
					target={target}
					style={style}>
					<AtrcText
						tag='span'
						className='screen-reader-text'>
						{decodeEntities(pattern.title.rendered)}
					</AtrcText>
					<AtrcImg
						src={pattern.featured_images.full.url}
						alt={decodeEntities(pattern.title.rendered)}
					/>
				</AtrcLink>
			)}

			{showCopyOrMessage && (
				<PatternCopyOrMessage
					pattern={pattern}
					showGuide={showGuide}
				/>
			)}
		</AtrcWrap>
	);
}

export default PatternThumbnail;
