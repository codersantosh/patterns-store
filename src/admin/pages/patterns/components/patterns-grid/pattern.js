/* WordPress */
import { __, sprintf } from '@wordpress/i18n';
import { decodeEntities } from '@wordpress/html-entities';

/* Library */
import classNames from 'classnames';

/* Atrc */
import {
    AtrcText,
    AtrcLink,
    AtrcHr,
    AtrcButton,
    AtrcWrap,
    AtrcImg,
    AtrcContent,
    AtrcButtonGroup,
} from 'atrc';

/* Inbuilt */
import PatternThumbnail from '../pattern-thumbnail';
import Pricing from '../pricing';

/* Local */
const SubTitle = ({ pattern }) => {
    if ('pattern-kit' === pattern['product-type']) {
        return (
            <AtrcText
                tag='span'
                className={classNames(
                    'ps-ls-itm-ttl-sub',
                    'at-flx',
                    'at-gap',
                    'at-al-itm-ctr',
                    'at-txt',
                    'at-m'
                )}>
                {sprintf(
                    __('%d patterns', 'patterns-store'),
                    (pattern.patterns && pattern.patterns.length) || 0
                )}
            </AtrcText>
        );
    }
    if (pattern['pattern-kit']) {
        return (
            <AtrcWrap
                tag='span'
                className={classNames(
                    'ps-ls-itm-ttl-sub',
                    'at-flx',
                    'at-gap',
                    'at-al-itm-ctr',
                    'at-txt',
                    'at-m'
                )}>
                {__('in', 'patterns-store')}
                <AtrcLink
                    target='_blank'
                    href={pattern['pattern-kit'].link}>
                    {pattern['pattern-kit'].title.rendered}
                </AtrcLink>
            </AtrcWrap>
        );
    }
    return null;
};

function Pattern({
    className = 'at-col-md-6 at-col-lg-3',
    pattern,
    onPreviewPatternChange,
    showThumbnail = true,
    showAuthor = true,
    showPrice = true,
    showTitle = true,
    showSubTitle = true,
    showPreview = true,
    showPurchase = true,
    showCopyOrMessage = true,
    showGuide = true,
    target = '_blank',
}) {
    return (
        <AtrcWrap className={className}>
            <AtrcWrap
                className={classNames('ps-ls-itm', 'at-bg-cl', 'ps-card', 'at-bdr')}>
                <PatternThumbnail
                    pattern={pattern}
                    showPrice={showPrice}
                    showThumbnail={showThumbnail}
                    showCopyOrMessage={showCopyOrMessage}
                    showGuide={showGuide}
                    target={target}
                />

                <AtrcContent className={classNames('at-p')}>
                    <AtrcWrap
                        className={classNames('at-flx', 'at-jfy-cont-btw', 'at-gap')}>
                        <AtrcWrap className={classNames('ps-ls-itm-ttl-wrp')}>
                            {showTitle && (
                                <AtrcText
                                    tag='h4'
                                    className={classNames('ps-ls-itm-ttl', 'at-m')}>
                                    <AtrcLink
                                        className={classNames('ps-ls-itm-ttl-lnk')}
                                        href={pattern.link}
                                        target={target}>
                                        {decodeEntities(pattern.title.rendered)}
                                    </AtrcLink>
                                </AtrcText>
                            )}
                            {showSubTitle && <SubTitle pattern={pattern} />}
                        </AtrcWrap>

                        {showPrice && (
                            <AtrcText
                                className={classNames(
                                    'ps-ls-itm-pricing',
                                    pattern?.price ? 'ps-pro' : 'ps-free'
                                )}
                                tag='span'
                                dangerouslySetInnerHTML={{
                                    __html: pattern?.price
                                        ? pattern.price
                                        : __('Free', 'patterns-store')
                                }}
                            />
                        )}
                    </AtrcWrap>

                    {(showAuthor && pattern.author_data) ||
                        (showPreview && onPreviewPatternChange) ||
                        showPurchase ? (
                        <>
                            <AtrcHr className={classNames('at-m')} />

                            <AtrcWrap className={classNames('at-flx')}>
                                {showAuthor && pattern.author_data ? (
                                    <AtrcLink
                                        href={pattern.author_data.url}
                                        className={classNames(
                                            'ps-auth-avatar',
                                            'at-flx-srnk-1',
                                            'at-flx',
                                            'at-gap',
                                            'at-al-itm-ctr'
                                        )}
                                        target={target}>
                                        <AtrcImg
                                            className={classNames(
                                                'ps-auth-avatar-img',
                                                'at-flx-srnk-1',
                                                'at-flx',
                                                'at-gap',
                                                'at-w',
                                                'at-h',
                                                'at-bdr-rad'
                                            )}
                                            alt={pattern.author_data.name}
                                            src={pattern.author_data.avatar}
                                        />
                                        {pattern.author_data.name}
                                    </AtrcLink>
                                ) : null}

                                {(showPreview && onPreviewPatternChange) || showPurchase ? (
                                    <AtrcButtonGroup
                                        className={classNames('at-flx-grw-1', 'at-jfy-cont-end')}>
                                        {showPreview && onPreviewPatternChange ? (
                                            <AtrcButton
                                                variant='outline-light'
                                                onClick={() => onPreviewPatternChange(pattern)}>
                                                {__('Preview', 'patterns-store')}
                                            </AtrcButton>
                                        ) : null}
                                        {showPurchase && <Pricing pattern={pattern} />}
                                    </AtrcButtonGroup>
                                ) : null}
                            </AtrcWrap>
                        </>
                    ) : null}
                </AtrcContent>
            </AtrcWrap>
        </AtrcWrap>
    );
}
export default Pattern;
