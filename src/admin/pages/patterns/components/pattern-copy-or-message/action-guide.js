/* Inbuilt */
import ModalGuideCopy from './modal-guide-copy';
import ModalPatternsGrid from './modal-patterns-grid';
import ModalPricing from '../pricing/modal-pricing';

/* Local */
const ActionGuide = ({ type, setMessageType, setGuideType, patterns, id }) => {
	if ('copy' === type) {
		return <ModalGuideCopy setGuideType={setGuideType} />;
	}

	if ('patterns' === type) {
		return (
			<ModalPatternsGrid
				patterns={patterns}
				setGuideType={setGuideType}
			/>
		);
	}

	if ('access-denied' === type) {
		return (
			<ModalPricing
				id={id}
				setShowModal={() => setGuideType('')}
			/>
		);
	}
	return null;
};

export default ActionGuide;
