/* WordPress */

/* Atrc */
import { AtrcWrap } from 'atrc';

/* Internal */
import Pattern from './pattern';
import EmptyResult from '../../pages/patterns/organisms/not-found';

/* Local */
function PatternsGrid(props) {
	const { patterns, isArchive, ...defaultProps } = props;

	if (!patterns || !patterns.length) {
		return <EmptyResult />;
	}

	return (
		<AtrcWrap className='at-row at-gap'>
			{patterns &&
				patterns.map((post) => (
					<Pattern
						className={
							isArchive ? 'at-col-md-4 at-col-lg-3' : 'at-col-md-6 at-col-lg-4'
						}
						key={post.id}
						pattern={post}
						{...defaultProps}
					/>
				))}
		</AtrcWrap>
	);
}
export default PatternsGrid;
