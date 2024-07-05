/* WordPress */
import { useState } from '@wordpress/element';

/* Atrc */
import { AtrcWrap } from 'atrc';

/* Inbuilt */
import CopyOrBuy from './copy-or-buy';
import ActionMsg from './action-msg';
import ActionGuide from './action-guide';

/* Local 
Message type copy, patterns, access-denied
*/
const PatternCopyOrMessage = ({ pattern, showGuide = true }) => {
	const {
		id,
		access,
		pattern_content,
		link,
		'product-type': productType,
		patterns,
	} = pattern;

	const [msgType, setMessageType] = useState('');
	const [guideType, setGuideType] = useState(false);

	return (
		<AtrcWrap className='ps-ls-itm-actions at-pos at-z-idx at-vis at-opa at-trs at-tf'>
			<CopyOrBuy
				setMessageType={(type) => setMessageType(type)}
				access={access}
				content={pattern_content}
				productType={productType}
				link={link}
				showGuide={showGuide}
			/>
			{msgType ? (
				<ActionMsg
					type={msgType}
					setGuideType={(type) => setGuideType(type)}
					setMessageType={(type) => setMessageType(type)}
				/>
			) : null}
			{guideType ? (
				<ActionGuide
					type={guideType}
					setGuideType={(type) => setGuideType(type)}
					patterns={patterns}
					id={id}
				/>
			) : null}
		</AtrcWrap>
	);
};

export default PatternCopyOrMessage;

export const BlocksPatternCopyOrMessage = ({
	pattern = {},
	messageType = '',
	popupType = '',
}) => {
	const { id, patterns } = pattern;

	const [msgType, setMessageType] = useState(messageType);
	const [guideType, setGuideType] = useState(popupType);

	return (
		<>
			{msgType ? (
				<ActionMsg
					type={msgType}
					setGuideType={(type) => setGuideType(type)}
					setMessageType={(type) => setMessageType(type)}
				/>
			) : null}
			{guideType ? (
				<ActionGuide
					type={guideType}
					setGuideType={(type) => setGuideType(type)}
					patterns={patterns}
					id={id}
				/>
			) : null}
		</>
	);
};
