/* WordPress */
import { __ } from '@wordpress/i18n';

/* Atrc */
import { AtrcModal } from 'atrc';

/* Inbuilt */
import PatternsGrid from '../patterns-grid';

/* Local */
const ModalPatternsGrid = ({ patterns, setGuideType }) => {
	return (
		<AtrcModal
			className={patterns && patterns.length ? '' : 'ps-404__modal'}
			bodyOpenClassName='ps-modal__open'
			onRequestClose={() => setGuideType('')}
			title={__(
				'Explore all patterns within this pattern kit.',
				'patterns-store'
			)}>
			<PatternsGrid
				patterns={patterns}
				showPurchase={false}
				showSubTitle={false}
				showAuthor={false}
			/>
		</AtrcModal>
	);
};

export default ModalPatternsGrid;
