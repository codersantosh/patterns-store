/* WordPress */
import { __ } from '@wordpress/i18n';
import { useState } from '@wordpress/element';

/* Atrc */
import { AtrcButton } from 'atrc';

/* Inbuilt */
import ModalPricing from './modal-pricing';
import ModalGuideCopy from '../pattern-copy-or-message/modal-guide-copy';

/* Local */
const Pricing = ({ pattern }) => {
	const [showModal, setShowModal] = useState(false);
	if (pattern.price) {
		return (
			<>
				<AtrcButton onClick={() => setShowModal(true)}>
					{__('Pricing', 'patterns-store')}
				</AtrcButton>
				{showModal && (
					<ModalPricing
						id={pattern.id}
						setShowModal={setShowModal}
					/>
				)}
			</>
		);
	}
	return (
		<>
			<AtrcButton onClick={() => setShowModal(true)}>
				{__('Get Free', 'patterns-store')}
			</AtrcButton>
			{showModal && (
				<ModalGuideCopy
					setGuideType={() => setShowModal(false)}
					title={__('Just copy and paste the Patterns', 'patterns-store')}
				/>
			)}
		</>
	);
};

export default Pricing;
