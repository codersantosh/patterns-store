/* WordPress */
import { __ } from '@wordpress/i18n';
import { useState, useEffect } from '@wordpress/element';

/* Library */
import classNames from 'classnames';

/* Atrc */
import {
	AtrcWrapFloating,
	AtrcNotice,
	AtrcWrap,
	AtrcText,
	AtrcButton,
} from 'atrc';

/* Local */
const ActionMsg = ({ type, setMessageType, setGuideType }) => {
	const [msgContent, setMessageContent] = useState({
		title: '',
		subTitle: '',
		btnText: '',
	});

	useEffect(() => {
		switch (type) {
			case 'copy':
				setMessageContent({
					title: __('Pattern copied!', 'patterns-store'),
					subTitle: __(
						'You can now paste it into any WordPress post or page.',
						'patterns-store'
					),
					btnText: __('Learn more', 'patterns-store'),
				});
				break;

			case 'patterns':
				setMessageContent({
					title: __('Pattern Kits', 'patterns-store'),
					subTitle: __(
						'Explore all patterns within this Pattern Kit and easily copy them.',
						'patterns-store'
					),
					btnText: __('View patterns', 'patterns-store'),
				});
				break;

			case 'access-denied':
				setMessageContent({
					title: __('Access denied!', 'patterns-store'),
					subTitle: __(
						'You need to purchase the item to gain access.',
						'patterns-store'
					),
					btnText: __('View pricing plan', 'patterns-store'),
				});
				break;

			default:
				break;
		}
	}, [type]);

	if (!msgContent.title) {
		return null;
	}

	return (
		<AtrcWrapFloating>
			<AtrcNotice
				autoDismiss={5000}
				status='success'
				isDismissible={true}
				onRemove={() => {
					setMessageType('');
					setGuideType('');
				}}
				onAutoRemove={() => {
					setMessageType('');
				}}>
				<AtrcWrap className={classNames('ps-action-msg')}>
					<AtrcText
						tag='h4'
						className={classNames('at-m', 'ps-action-msg-ttl')}>
						{msgContent.title}
					</AtrcText>
					<AtrcText className={classNames('at-m', 'ps-action-msg-ttl-sub')}>
						{msgContent.subTitle}
					</AtrcText>
					<AtrcButton
						isPrimary
						onClick={() => setGuideType(type)}>
						{msgContent.btnText}
					</AtrcButton>
				</AtrcWrap>
			</AtrcNotice>
		</AtrcWrapFloating>
	);
};

export default ActionMsg;
