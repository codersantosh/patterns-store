/* WordPress */
import { useState, useEffect, useMemo } from '@wordpress/element';

/* Library */
import classNames from 'classnames';

/* Atrc */
import {
    AtrcLi,
    AtrcModal,
    AtrcSpinner,
    AtrcList,
    AtrcText,
    AtrcWrap,
    AtrcIcon,
    AtrcButton,
} from 'atrc';

/* Inbuilt */
import FilterProductTypes, { getCurrentPlanInfo } from './filter-product-types';
import FilterLicense, { getCurrentLicenseInfo } from './filter-license';
import FilterRecurring, { getCurrentRecurringInfo } from './filter-recurring';
import PatternsStoreGetColClasses, {
    PatternsStoreFilterPricingIsShow,
} from '../../utils';

/* Local */
const PricingContent = ({ checkoutData, setShowModal }) => {
    const {
        initial,
        filter,
        filter_product_types,
        filter_licenses,
        filter_recurring,
    } = checkoutData;

    const [filterProductType, setFilterProductType] = useState(
        initial['product-type']
    );

    const [filterLicense, setFilterLicense] = useState(
        filter_licenses && filter_licenses.length ? filter_licenses[0] : 0
    );

    const [filterRecurring, setFilterRecurring] = useState(
        filter_recurring && filter_recurring.length ? filter_recurring[0] : 0
    );

    const { pricingData } = PatternsStoreLocalize;

    const filteredItems = useMemo(() => {
        return filter.filter((item) => {
            return PatternsStoreFilterPricingIsShow({
                item,
                filterProductType,
                filterLicense,
                filterRecurring,
            });
        });
    }, [filter, filterProductType, filterLicense, filterRecurring]);

    const atColClasses = useMemo(() => {
        return PatternsStoreGetColClasses(filteredItems.length);
    }, [filteredItems]);

    return (
        <AtrcWrap className='ps-pricing__modal--cont at-pos'>
            <AtrcButton
                isLink
                variant=''
                className='at-pos ps-modal__close'
                onClick={() => setShowModal(false)}>
                <AtrcIcon
                    type='svg'
                    svg='<svg xmlns="http://www.w3.org/2000/svg" class="at-svg" viewBox="0 0 384 512"><path d="M342.6 150.6c12.5-12.5 12.5-32.8 0-45.3s-32.8-12.5-45.3 0L192 210.7 86.6 105.4c-12.5-12.5-32.8-12.5-45.3 0s-12.5 32.8 0 45.3L146.7 256 41.4 361.4c-12.5 12.5-12.5 32.8 0 45.3s32.8 12.5 45.3 0L192 301.3 297.4 406.6c12.5 12.5 32.8 12.5 45.3 0s12.5-32.8 0-45.3L237.3 256 342.6 150.6z"/></svg>'
                />
            </AtrcButton>
            <AtrcWrap className='at-txt-al ps-pricing__modal--cont-hdr at-m'>
                <AtrcText
                    tag='h1'
                    className='at-m'>
                    {pricingData.title}
                </AtrcText>
                <AtrcText
                    tag='p'
                    className={classNames('at-m')}>
                    {pricingData.subtitle}
                </AtrcText>
            </AtrcWrap>
            <AtrcWrap className='at-flx at-flx-col at-flx-md-row at-al-itm-ctr at-gap at-jfy-cont-btw ps-pricing__modal--cont-flt at-m'>
                {filter_recurring && filter_recurring.length && pricingData.recView ? (
                    <FilterRecurring
                        availableValues={filter_recurring}
                        value={filterRecurring}
                        setFilteValue={(newVal) => {
                            setFilterRecurring(newVal);
                        }}
                    />
                ) : null}
                {filter_licenses && filter_licenses.length && pricingData.licView ? (
                    <FilterLicense
                        availableValues={filter_licenses}
                        value={filterLicense}
                        setFilteValue={(newVal) => {
                            setFilterLicense(newVal);
                        }}
                    />
                ) : null}

                {filter_product_types &&
                    filter_product_types.length &&
                    pricingData.planView.view ? (
                    <FilterProductTypes
                        availableValues={filter_product_types}
                        value={filterProductType}
                        setFilteValue={(newVal) => {
                            setFilterProductType(newVal);
                        }}
                    />
                ) : null}
            </AtrcWrap>

            <AtrcWrap className={classNames('at-row', 'at-gap')}>
                {filteredItems.map((item, iDx) => {
                    const currentProductInfo = getCurrentPlanInfo(item['product-type']);

                    let currentLicenseInfo;
                    if ('license' in item) {
                        currentLicenseInfo = getCurrentLicenseInfo(item['license']);
                    }

                    let currentRecurringInfo;
                    if ('recurring' in item) {
                        currentRecurringInfo = getCurrentRecurringInfo(item['recurring']);
                    }

                    return (
                        <AtrcWrap
                            className={atColClasses}
                            key={iDx}>
                            <AtrcWrap className='ps-card at-bdr at-p'>
                                {pricingData.planContent.sort.map((sortItem) => {
                                    const showItem = pricingData.planContent[sortItem];
                                    switch (sortItem) {
                                        case 'title':
                                            return showItem &&
                                                currentProductInfo &&
                                                currentProductInfo.title ? (
                                                <AtrcText
                                                    tag='h5'
                                                    className='at-m ps-plan__ttl'>
                                                    {currentProductInfo.title}
                                                </AtrcText>
                                            ) : null;
                                            break;
                                        case 'subTitle':
                                            return showItem &&
                                                currentProductInfo &&
                                                currentProductInfo.subtitle ? (
                                                <AtrcText
                                                    tag='p'
                                                    className='at-m ps-plan__ttl-sub'>
                                                    {currentProductInfo.subtitle}
                                                </AtrcText>
                                            ) : null;
                                            break;
                                        case 'price':
                                            return showItem && item.price ? (
                                                <AtrcText
                                                    tag='h3'
                                                    className='ps-plan__price at-m'>
                                                    {item.price}
                                                </AtrcText>
                                            ) : null;
                                            break;

                                        case 'purchaseButton':
                                            return showItem && item['pricing-html'] ? (
                                                <AtrcWrap
                                                    className='ps-plan__btn--purchase'
                                                    dangerouslySetInnerHTML={{
                                                        __html: item['pricing-html'],
                                                    }}
                                                />
                                            ) : null;
                                            break;

                                        case 'planDesc':
                                            return showItem &&
                                                currentProductInfo &&
                                                currentProductInfo.desc ? (
                                                <AtrcList className='ps-plan__desc at-m at-bdr at-p at-gap at-flx at-flx-col'>
                                                    {currentProductInfo.desc.map((itm) => (
                                                        <AtrcLi className='at-flx at-gap at-al-itm-st at-txt at-bdr at-p'>
                                                            <AtrcIcon
                                                                type='svg'
                                                                svg='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16">  <path d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5z"/><path d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0"/></svg>'
                                                            />
                                                            {itm}
                                                        </AtrcLi>
                                                    ))}
                                                </AtrcList>
                                            ) : null;
                                            break;

                                        case 'activationInfo':
                                            return showItem &&
                                                currentLicenseInfo &&
                                                currentLicenseInfo.filterLabel ? (
                                                <AtrcText>{currentLicenseInfo.filterLabe}</AtrcText>
                                            ) : null;
                                            break;

                                        case 'licenseDesc':
                                            return showItem &&
                                                currentLicenseInfo &&
                                                currentLicenseInfo.desc ? (
                                                <AtrcList className='ps-plan__desc at-m at-bdr at-p at-gap at-flx at-flx-col'>
                                                    {currentLicenseInfo.desc.map((itm) => (
                                                        <AtrcLi className='at-flx at-gap at-al-itm-st at-txt at-bdr at-p'>
                                                            <AtrcIcon
                                                                type='svg'
                                                                svg='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16">  <path d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5z"/><path d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0"/></svg>'
                                                            />
                                                            {itm}
                                                        </AtrcLi>
                                                    ))}
                                                </AtrcList>
                                            ) : null;
                                            break;

                                        case 'recurringInfo':
                                            return showItem &&
                                                currentRecurringInfo &&
                                                currentRecurringInfo.filterLabel ? (
                                                <AtrcText>{currentRecurringInfo.filterLabel}</AtrcText>
                                            ) : null;
                                            break;

                                        case 'recurringDesc':
                                            return showItem &&
                                                currentRecurringInfo &&
                                                currentRecurringInfo.desc ? (
                                                <AtrcList className='ps-plan__desc at-m at-bdr at-p at-gap at-flx at-flx-col'>
                                                    {currentRecurringInfo.desc.map((itm) => (
                                                        <AtrcLi className='at-flx at-gap at-al-itm-st at-txt at-bdr at-p'>
                                                            <AtrcIcon
                                                                type='svg'
                                                                svg='<svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="at-svg" viewBox="0 0 16 16">  <path d="M3 14.5A1.5 1.5 0 0 1 1.5 13V3A1.5 1.5 0 0 1 3 1.5h8a.5.5 0 0 1 0 1H3a.5.5 0 0 0-.5.5v10a.5.5 0 0 0 .5.5h10a.5.5 0 0 0 .5-.5V8a.5.5 0 0 1 1 0v5a1.5 1.5 0 0 1-1.5 1.5z"/><path d="m8.354 10.354 7-7a.5.5 0 0 0-.708-.708L8 9.293 5.354 6.646a.5.5 0 1 0-.708.708l3 3a.5.5 0 0 0 .708 0"/></svg>'
                                                            />
                                                            {itm}
                                                        </AtrcLi>
                                                    ))}
                                                </AtrcList>
                                            ) : null;
                                            break;

                                        case 'productTypeInfo':
                                            return showItem &&
                                                currentProductInfo &&
                                                currentProductInfo.filterLabel ? (
                                                <AtrcText>{currentProductInfo.filterLabel}</AtrcText>
                                            ) : null;
                                            break;

                                        default:
                                            break;
                                    }
                                })}
                            </AtrcWrap>
                        </AtrcWrap>
                    );
                })}
            </AtrcWrap>
        </AtrcWrap>
    );
};

/* Global state of pricing data */
const GlobalPricingData = {};

const ModalPricing = ({ id, setShowModal }) => {
    const { pricingData } = PatternsStoreLocalize;

    const [checkoutData, setCheckoutData] = useState(null);
    const [loading, setLoading] = useState(true);

    const fetchPricing = async (productId) => {
        try {
            const response = await fetch(
                `${PatternsStoreLocalize.rest_url}patterns-store/v1/pricing/${productId}`
            );
            const responseJson = await response.json();
            GlobalPricingData[id] = responseJson;
            setCheckoutData(responseJson);
        } catch (error) {
            console.error(error);
            setCheckoutData(
                __('Error fetching pricing. Please try again later.', 'patterns-store')
            );
        } finally {
            setLoading(false);
        }
    };

    useEffect(() => {
        if (GlobalPricingData[id]) {
            setCheckoutData(GlobalPricingData[id]);
            setLoading(false);
        } else {
            fetchPricing(id);
        }
    }, [id]);

    const memoizedPricingContent = useMemo(
        () => (
            <PricingContent
                checkoutData={checkoutData}
                setShowModal={setShowModal}
            />
        ),
        [checkoutData]
    );

    return (
        <AtrcModal
            className='ps-pricing__modal'
            __experimentalHideHeader
            bodyOpenClassName='ps-modal__open'
            title={checkoutData && pricingData.title}
            shouldCloseOnClickOutside={false}
            onRequestClose={() => setShowModal(false)}>
            {loading ? <AtrcSpinner /> : memoizedPricingContent}
        </AtrcModal>
    );
};

export default ModalPricing;
