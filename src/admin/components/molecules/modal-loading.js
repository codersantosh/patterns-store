/* WordPress */

/* Library */
import classNames from 'classnames';

/*Atrc*/
import { AtrcModal, AtrcWrap, AtrcSpinner } from 'atrc';

/* Inbuilt */

/*Local*/
const ModalLoading = () => {
    const style = {
        width: '100%',
        height: '100%',
    };
    return (
        <AtrcModal
            className='ps-preview__modal--loading'
            __experimentalHideHeader={true}
            bodyOpenClassName='ps-preview__modal-open'
            isFullScreen={true}
            isDismissible={false}
            shouldCloseOnClickOutside={false}
            style={style}>
            <AtrcWrap
                className={classNames(
                    'at-h',
                    'ps-preview__modal--main-loading',
                    'at-bg-cl',
                    'at-flx',
                    'at-jfy-cont-ctr',
                    'at-al-itm-ctr'
                )}>
                <AtrcSpinner />
            </AtrcWrap>
        </AtrcModal>
    );
};

export default ModalLoading;
