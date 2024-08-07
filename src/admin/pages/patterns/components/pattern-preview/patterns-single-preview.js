/* WordPress */
import { decodeEntities } from '@wordpress/html-entities';
import { useState, useEffect } from '@wordpress/element';
import { addQueryArgs } from '@wordpress/url';
import { __ } from '@wordpress/i18n';

/* Library */
import classNames from 'classnames';
import { cloneDeep } from 'lodash';

/* Atrc */
import {
    AtrcButton,
    AtrcImg,
    AtrcModal,
    AtrcText,
    AtrcWrap,
    AtrcIcon,
    AtrcButtonGroup,
    AtrcHr,
} from 'atrc';

/* Internal */
import Canvas from './canvas';
import PatternCopyOrMessage from '../pattern-copy-or-message';
import Pattern from '../patterns-grid/pattern';


/* Local 
Initial product is the product which first trigger this component.
Main product is the product which details is used for the main preview for the Header part. It is required because of Pattern Kits.
Related items are patterns for pattern kits and all posts for pattern.
*/
const PatternSinglePreview = ({
    currentPattern,
    onPreviewPatternChange,
    posts = null,
}) => {
    const [initialProduct, setInitialProduct] = useState(null);
    const [mainProduct, setMainProduct] = useState(null);
    const [relatedItems, setRelatedItems] = useState(null);
    const [prevNextPattern, setPrevNextPattern] = useState({
        prev: null,
        next: null,
    });

    const [hideControl, setHideControl] = useState(false);
    const [deviceControl, setDeviceControl] = useState('');

    /* Related items are directly related to the first currentPattern that is loaded to this component,
    for pattern kits patterns and for pattern all posts.
    Also added initial Product.
    */
    useEffect(() => {
        if (currentPattern) {
            if ('pattern-kit' === currentPattern['product-type']) {
                if (currentPattern.patterns) {
                    const items = cloneDeep(currentPattern.patterns);

                    /* For pattern first preview is items 0 */
                    if (items && items[0]) {
                        onPreviewPatternChange(items[0]);
                    }
                    setRelatedItems(items);
                }
            } else {
                setRelatedItems(posts);
            }
        }

        setInitialProduct(currentPattern);
        return () => {
            setRelatedItems(null);
            setInitialProduct(null);
        };
    }, []);

    /* mainProduct is initial product for Pattern Kit but each time different for Pattern*/
    useEffect(() => {
        if (initialProduct && currentPattern) {
            if ('pattern-kit' === initialProduct['product-type']) {
                setMainProduct(initialProduct);
            } else {
                setMainProduct(currentPattern);
            }
        }
    }, [initialProduct, currentPattern]);

    /* Set prev and next item */
    useEffect(() => {
        if (currentPattern && relatedItems && Array.isArray(relatedItems)) {
            const index = relatedItems.findIndex(
                (obj) => obj.id === currentPattern.id
            );

            if (index !== -1) {
                // Set prev and next based on the found index
                const prev = index > 0 ? relatedItems[index - 1] : null;
                const next =
                    index < relatedItems.length - 1 ? relatedItems[index + 1] : null;

                setPrevNextPattern({
                    prev,
                    next,
                });
            } else {
                // Handle case when pattern is not found
                setPrevNextPattern({
                    prev: null,
                    next: null,
                });
            }
        } else {
            // Handle case when pattern or relatedItems are not defined
            setPrevNextPattern({
                prev: null,
                next: null,
            });
        }
    }, [currentPattern, relatedItems]);

    if (!mainProduct || !mainProduct.link) {
        return null;
    }

    const style = {
        width: '100%',
        height: '100%',
    };

    return (
        <AtrcModal
            className='ps-preview__modal'
            __experimentalHideHeader={true}
            bodyOpenClassName='ps-preview__modal-open'
            isFullScreen={true}
            isDismissible={false}
            shouldCloseOnClickOutside={false}
            onRequestClose={() => onPreviewPatternChange(null)}
            style={style}>
            <AtrcWrap
                className={classNames(
                    'ps-preview__modal--body',
                    'at-h',
                    'at-pos',
                    'at-w',
                    deviceControl ? 'at-bg-cl' : '',
                    hideControl ? 'ps-preview__modal--hide-ctrl' : '',
                    deviceControl ? 'ps-preview__modal--device-' + deviceControl : ''
                )}>
                <AtrcWrap className='ps-preview__modal--sidebar at-pos at-z-idx at-trs at-w at-h at-bg-cl at-bdr at-box-szg'>
                    <AtrcWrap className='ps-preview__modal--hdr at-pos at-z-idx at-bg-cl at-bdr'>
                        <AtrcButtonGroup className='ps-preview__modal--hdr-actions'>
                            <AtrcButton
                                variant='light'
                                hasIcon='true'
                                className='ps-preview__modal--close at-m at-h'
                                onClick={() => onPreviewPatternChange(null)}>
                                <AtrcIcon
                                    type='svg'
                                    svg='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16">  <path d="M2.146 2.854a.5.5 0 1 1 .708-.708L8 7.293l5.146-5.147a.5.5 0 0 1 .708.708L8.707 8l5.147 5.146a.5.5 0 0 1-.708.708L8 8.707l-5.146 5.147a.5.5 0 0 1-.708-.708L7.293 8z"/></svg>'
                                />
                            </AtrcButton>
                            <AtrcButton
                                variant='light'
                                hasIcon='true'
                                className='ps-preview__modal--prev at-h at-m'
                                disabled={!prevNextPattern.prev}
                                onClick={() => {
                                    if (prevNextPattern.prev) {
                                        onPreviewPatternChange(prevNextPattern.prev);
                                    }
                                }}>
                                <AtrcIcon
                                    type='svg'
                                    svg='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M11.354 1.646a.5.5 0 0 1 0 .708L5.707 8l5.647 5.646a.5.5 0 0 1-.708.708l-6-6a.5.5 0 0 1 0-.708l6-6a.5.5 0 0 1 .708 0"/></svg>'
                                />
                            </AtrcButton>
                            <AtrcButton
                                variant='light'
                                hasIcon='true'
                                className='ps-preview__modal--next at-h'
                                disabled={!prevNextPattern.next}
                                onClick={() => {
                                    if (prevNextPattern.next) {
                                        onPreviewPatternChange(prevNextPattern.next);
                                    }
                                }}>
                                <AtrcIcon
                                    type='svg'
                                    svg='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"> <path fill-rule="evenodd" d="M4.646 1.646a.5.5 0 0 1 .708 0l6 6a.5.5 0 0 1 0 .708l-6 6a.5.5 0 0 1-.708-.708L10.293 8 4.646 2.354a.5.5 0 0 1 0-.708"/></svg>'
                                />
                            </AtrcButton>
                        </AtrcButtonGroup>
                    </AtrcWrap>
                    <AtrcWrap className='ps-preview__modal--cont at-p at-z-idx at-pos at-w at-h at-ovf at-d-non at-lg-blk'>
                        <>
                            {mainProduct ? (
                                <AtrcWrap className='ps-preview__modal--cont-itm'>
                                    <AtrcText tag='h4'>
                                        {decodeEntities(mainProduct.title.rendered)}
                                    </AtrcText>
                                    <AtrcWrap className='ps-ls-itm-preview at-pos at-z-idx'>
                                        <AtrcImg
                                            src={mainProduct.featured_images.full.url}
                                            alt={decodeEntities(mainProduct.title.rendered)}
                                        />
                                        <PatternCopyOrMessage
                                            pattern={mainProduct}
                                            showGuide={false}
                                        />
                                    </AtrcWrap>
                                    <AtrcHr className='at-m' />
                                </AtrcWrap>
                            ) : null}

                            {mainProduct &&
                                'pattern-kit' === mainProduct['product-type'] &&
                                relatedItems ? (
                                <AtrcWrap className='ps-preview__modal--cont-ls at-flx at-flx-col at-gap'>
                                    {relatedItems &&
                                        relatedItems.map((post) => (
                                            <Pattern
                                                key={post.id}
                                                pattern={post}
                                                className={classNames(
                                                    'ps-preview__modal--cont-ls-itm',
                                                    'at-opa',
                                                    'at-trs',
                                                    post.id === currentPattern.id ? 'ps-current-itm' : '',
                                                    deviceControl ? 'ps-device-' + deviceControl : ''
                                                )}
                                                showPurchase={false}
                                                showSubTitle={false}
                                                showAuthor={true}
                                                showGuide={false}
                                                showPreview={true}
                                                onPreviewPatternChange={onPreviewPatternChange}
                                                target='__blank'
                                            />
                                        ))}
                                </AtrcWrap>
                            ) : null}
                        </>
                    </AtrcWrap>
                    <AtrcWrap className='ps-preview__modal--ftr at-pos at-w  at-bdr at-bg-cl at-d-non at-lg-blk'>
                        <AtrcWrap className='at-h at-flx at-al-itm-strh at-jfy-cont-btw'>
                            <AtrcButton
                                hasIcon='true'
                                variant=''
                                className={classNames(
                                    'ps-preview__collapse--btn',
                                    hideControl ? 'at-pos at-z-idx' : ''
                                )}
                                onClick={() => setHideControl(!hideControl)}>
                                <AtrcIcon
                                    className={classNames(
                                        'ps-preview__collapse--arrow',
                                        'at-trs',
                                        'at-tf',
                                        hideControl ? 'ps-preview__collapse--arrow-open' : ''
                                    )}
                                    type='svg'
                                    svg='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M6.79 5.093A.5.5 0 0 0 6 5.5v5a.5.5 0 0 0 .79.407l3.5-2.5a.5.5 0 0 0 0-.814z"/></svg>'
                                />
                                {!hideControl ? (
                                    <AtrcText
                                        tag='span'
                                        className='ps-preview__collapse-lbl'>
                                        {__('Hide control', 'patterns-store')}
                                    </AtrcText>
                                ) : null}
                            </AtrcButton>

                            {!hideControl ? (
                                <AtrcButtonGroup className='ps-preview__modal--devices'>
                                    <AtrcButton
                                        variant=''
                                        isActive={'' === deviceControl}
                                        aria-pressed='true'
                                        data-device='desktop'
                                        onClick={() => setDeviceControl('')}>
                                        <AtrcText
                                            tag='span'
                                            className='screen-reader-text'>
                                            {__('Enter desktop preview mode', 'patterns-store')}
                                        </AtrcText>
                                        <AtrcIcon
                                            type='svg'
                                            svg='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"><path d="M0 4s0-2 2-2h12s2 0 2 2v6s0 2-2 2h-4q0 1 .25 1.5H11a.5.5 0 0 1 0 1H5a.5.5 0 0 1 0-1h.75Q6 13 6 12H2s-2 0-2-2zm1.398-.855a.76.76 0 0 0-.254.302A1.5 1.5 0 0 0 1 4.01V10c0 .325.078.502.145.602q.105.156.302.254a1.5 1.5 0 0 0 .538.143L2.01 11H14c.325 0 .502-.078.602-.145a.76.76 0 0 0 .254-.302 1.5 1.5 0 0 0 .143-.538L15 9.99V4c0-.325-.078-.502-.145-.602a.76.76 0 0 0-.302-.254A1.5 1.5 0 0 0 13.99 3H2c-.325 0-.502.078-.602.145"/></svg>'
                                        />
                                    </AtrcButton>
                                    <AtrcButton
                                        variant=''
                                        isActive={'t' === deviceControl}
                                        aria-pressed='false'
                                        data-device='tablet'
                                        onClick={() => setDeviceControl('t')}>
                                        <AtrcText
                                            tag='span'
                                            className='screen-reader-text'>
                                            {__('Enter tablet preview mode', 'patterns-store')}
                                        </AtrcText>
                                        <AtrcIcon
                                            type='svg'
                                            svg='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"> <path d="M12 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H4a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM4 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/><path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/></svg>'
                                        />
                                    </AtrcButton>
                                    <AtrcButton
                                        variant=''
                                        isActive={'m' === deviceControl}
                                        aria-pressed='false'
                                        data-device='mobile'
                                        onClick={() => setDeviceControl('m')}>
                                        <AtrcText
                                            tag='span'
                                            className='screen-reader-text'>
                                            {__('Enter mobile preview mode', 'patterns-store')}
                                        </AtrcText>
                                        <AtrcIcon
                                            type='svg'
                                            svg='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16"> <path d="M11 1a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H5a1 1 0 0 1-1-1V2a1 1 0 0 1 1-1zM5 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2z"/><path d="M8 14a1 1 0 1 0 0-2 1 1 0 0 0 0 2"/></svg>'
                                        />
                                    </AtrcButton>
                                </AtrcButtonGroup>
                            ) : null}
                        </AtrcWrap>
                    </AtrcWrap>
                </AtrcWrap>
                <AtrcWrap
                    className={classNames(
                        'at-trs',
                        'at-p',
                        'at-pos',
                        'at-h',
                        'at-w',
                        'ps-preview__modal--main',
                        'at-bg-cl',
                        'at-tf',
                        deviceControl ? 'at-m' : ''
                    )}>
                    <Canvas
                        url={addQueryArgs(currentPattern.link, {
                            view: 'patterns-store-pattern-only',
                        })}
                    />
                </AtrcWrap>
            </AtrcWrap>
        </AtrcModal>
    );
};

export default PatternSinglePreview;
