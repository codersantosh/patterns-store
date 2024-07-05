/* WordPress */
import { __ } from '@wordpress/i18n';
import { createInterpolateElement } from '@wordpress/element';

/* Library */
import classnames from 'classnames';

/* Atrc */
import {
	AtrcImg,
	AtrcLi,
	AtrcList,
	AtrcText,
	AtrcModal,
	AtrcButton,
	AtrcIcon,
	AtrcWrap,
} from 'atrc';

/* Local */
const CopyPasteImage = () => (
	<AtrcImg
		className={classnames('at-m')}
		src={`${PatternsStoreLocalize.PATTERNS_STORE_URL}assets/img/copy-paste-demo.gif`}
		alt={__('GIF of copy and pasting.', 'patterns-store')}
	/>
);

const ModalGuideCopy = ({
	setGuideType,
	title = __('How to use patterns on your WordPress site.', 'patterns-store'),
}) => {
	return (
		<AtrcModal
			className={classnames('ps-guide__modal')}
			__experimentalHideHeader
			bodyOpenClassName='ps-modal__open'
			onRequestClose={() => setGuideType('')}>
			<AtrcWrap className={classnames('ps-modal--cont', 'at-pos')}>
				<AtrcButton
					isLink
					variant=''
					className={classnames('at-pos', 'ps-modal__close')}
					onClick={() => setGuideType('')}>
					<AtrcIcon
						type='svg'
						svg='<svg xmlns="http://www.w3.org/2000/svg" class="at-svg" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>'
					/>
				</AtrcButton>

				<AtrcText
					tag='h4'
					className={classnames('ps-guide__modal--ttl', 'at-m')}>
					{title}
				</AtrcText>
				<CopyPasteImage />
				<AtrcText
					tag='p'
					className={classnames('ps-guide__modal--desc')}>
					{__(
						'Patterns are really just text. And, just like you can copy and paste text, you can copy and paste patterns. It’s really easy!',
						'patterns-store'
					)}
				</AtrcText>
				<AtrcList
					type='ol'
					className={classnames('at-m', 'at-flx', 'at-flx-col', 'at-gap')}>
					<AtrcLi>
						<AtrcText
							tag='p'
							className={classnames('at-m')}>
							{__(
								'Open any post or page in the WordPress block editor.',
								'patterns-store'
							)}
						</AtrcText>
					</AtrcLi>
					<AtrcLi>
						<AtrcText
							tag='p'
							className={classnames('at-m')}>
							{__(
								'Place your cursor where you want to add the pattern.',
								'patterns-store'
							)}
						</AtrcText>
					</AtrcLi>
					<AtrcLi>
						<AtrcText
							tag='p'
							className={classnames('at-m')}>
							{createInterpolateElement(
								__(
									'Paste the contents of your clipboard by holding down <kbd>ctrl</kbd> control (Windows) or <kbd>⌘</kbd> command (Mac) and pressing the <kbd>v</kbd> key, or right-clicking and choose “Paste” from the menu.',
									'patterns-store'
								),
								{
									kbd: (
										<kbd className='ps-guide__shortcut at-bg-cl at-bdr at-p' />
									),
								}
							)}
						</AtrcText>
					</AtrcLi>
				</AtrcList>
			</AtrcWrap>
		</AtrcModal>
	);
};

export default ModalGuideCopy;
