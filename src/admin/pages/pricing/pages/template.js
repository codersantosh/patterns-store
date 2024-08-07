/* WordPress */
import { __ } from '@wordpress/i18n';

import { useContext } from '@wordpress/element';

/* Library */
import classNames from 'classnames';

import { cloneDeep } from 'lodash';

/*Atrc*/
import {
    AtrcSpinner,
    AtrcTitleTemplate2,
    AtrcWireFrameContentSidebar,
    AtrcWireFrameHeaderContentFooter,
    AtrcPrefix,
    AtrcPanelBody,
    AtrcPanelRow,
    AtrcText,
    AtrcControlSelect,
    AtrcControlText,
    AtrcControlSortable,
    AtrcControlCheckbox,
    AtrcNestedObjUpdateByKey1,
    AtrcNestedObjUpdateByKey2,
} from 'atrc';

/* Inbuilt */
import { AtrcReduxContextData } from '../../../routes';
import { DocsTitle } from '../../../components/molecules';

/*Local*/
const MainContent = () => {
    const data = useContext(AtrcReduxContextData);

    const { dbSettings, dbUpdateSetting } = data;

    const { pricing = {} } = dbSettings;

    const {
        template = 0,
        title = '',
        subtitle = '',
        planView = {},
        planContent = {},
        licView,
        recView,
        onLicense,
    } = pricing;

    return (
        <>
            {/* Pricing Template */}
            <AtrcPanelRow className={classNames('at-m')}>
                <AtrcControlText
                    label={__('Pricing main title', 'patterns-store')}
                    value={title}
                    onChange={(newVal) => {
                        const updatedSettings = AtrcNestedObjUpdateByKey1({
                            settings: pricing,
                            key1: 'title',
                            val1: newVal,
                        });
                        dbUpdateSetting('pricing', updatedSettings);
                    }}
                />
            </AtrcPanelRow>
            <AtrcPanelRow className={classNames('at-m')}>
                <AtrcControlText
                    label={__('Pricing main subtitle', 'patterns-store')}
                    value={subtitle}
                    onChange={(newVal) => {
                        const updatedSettings = AtrcNestedObjUpdateByKey1({
                            settings: pricing,
                            key1: 'subtitle',
                            val1: newVal,
                        });
                        dbUpdateSetting('pricing', updatedSettings);
                    }}
                />
            </AtrcPanelRow>
            <AtrcPanelRow className={classNames('at-m')}>
                <AtrcPanelBody
                    className={classNames(AtrcPrefix('m-0'), 'at-flx-grw-1')}
                    title={__('Plan view', 'patterns-store')}
                    initialOpen={true}>
                    <AtrcPanelRow className={classNames('at-m')}>
                        <AtrcControlSelect
                            label={__('Pricing column view', 'patterns-store')}
                            help={
                                'variation' === planView.column
                                    ? __(
                                        'Display pricing columns based on product type, such as pattern, pattern kit, and all-access plan. Make sure that you have chosen the "All Access" product and/or have not disabled pattern kits.',
                                        'patterns-store'
                                    )
                                    : __(
                                        'Display pricing columns based on single item variations type, such as such as licenses and recurring payments.',
                                        'patterns-store'
                                    )
                            }
                            wrapProps={{
                                className: 'at-flx-grw-1',
                            }}
                            value={planView.column}
                            options={[
                                {
                                    value: '',
                                    label: __('Product type wise pricing', 'patterns-store'),
                                },
                                {
                                    value: 'variation',
                                    label: __('Individual variation pricing', 'patterns-store'),
                                },
                            ]}
                            onChange={(newVal) => {
                                const updatedSettings = AtrcNestedObjUpdateByKey2({
                                    settings: pricing,
                                    key1: 'planView',
                                    key2: 'column',
                                    val2: newVal,
                                });
                                dbUpdateSetting('pricing', updatedSettings);
                            }}
                        />
                    </AtrcPanelRow>
                    <AtrcPanelRow className={classNames('at-m')}>
                        <AtrcControlSelect
                            label={__('Plan view', 'patterns-store')}
                            help={__(
                                'The plans are customizable on variation of item and can be tailored to suit individual, professional, business, and agency needs as per your specifications.',
                                'patterns-store'
                            )}
                            wrapProps={{
                                className: 'at-flx-grw-1',
                            }}
                            value={planView.view}
                            options={[
                                {
                                    value: '',
                                    label: __('Hide', 'patterns-store'),
                                },
                                {
                                    value: 'select',
                                    label: __('Select', 'patterns-store'),
                                },
                                {
                                    value: 'button',
                                    label: __('Button', 'patterns-store'),
                                },
                            ]}
                            onChange={(newVal) => {
                                const updatedSettings = AtrcNestedObjUpdateByKey2({
                                    settings: pricing,
                                    key1: 'planView',
                                    key2: 'view',
                                    val2: newVal,
                                });
                                dbUpdateSetting('pricing', updatedSettings);
                            }}
                        />
                    </AtrcPanelRow>

                    <AtrcPanelRow className={classNames('at-flx-col', 'at-al-itm-st')}>
                        <AtrcControlSortable
                            label={__(
                                'Enable, disable and arrange pricing plan column content',
                                'patterns-store'
                            )}
                            onChange={(newVal) => {
                                const updatedSettings = AtrcNestedObjUpdateByKey2({
                                    settings: pricing,
                                    key1: 'planContent',
                                    key2: 'sort',
                                    val2: newVal,
                                });
                                dbUpdateSetting('pricing', updatedSettings);
                            }}
                            value={planContent.sort}
                            items={[
                                {
                                    value: 'price',
                                    children: (
                                        <AtrcControlCheckbox
                                            label={__('Price', 'patterns-store')}
                                            checked={planContent.price}
                                            onChange={(newVal) => {
                                                const updatedSettings = AtrcNestedObjUpdateByKey2({
                                                    settings: pricing,
                                                    key1: 'planContent',
                                                    key2: 'price',
                                                    val2: newVal,
                                                });
                                                dbUpdateSetting('pricing', updatedSettings);
                                            }}
                                        />
                                    ),
                                },
                                {
                                    value: 'title',
                                    children: (
                                        <AtrcControlCheckbox
                                            label={__('Plan title', 'patterns-store')}
                                            checked={planContent.title}
                                            onChange={(newVal) => {
                                                const updatedSettings = AtrcNestedObjUpdateByKey2({
                                                    settings: pricing,
                                                    key1: 'planContent',
                                                    key2: 'title',
                                                    val2: newVal,
                                                });
                                                dbUpdateSetting('pricing', updatedSettings);
                                            }}
                                        />
                                    ),
                                },
                                {
                                    value: 'subTitle',
                                    children: (
                                        <AtrcControlCheckbox
                                            label={__('Plan subtitle', 'patterns-store')}
                                            checked={planContent.subTitle}
                                            onChange={(newVal) => {
                                                const updatedSettings = AtrcNestedObjUpdateByKey2({
                                                    settings: pricing,
                                                    key1: 'planContent',
                                                    key2: 'subTitle',
                                                    val2: newVal,
                                                });
                                                dbUpdateSetting('pricing', updatedSettings);
                                            }}
                                        />
                                    ),
                                },
                                {
                                    value: 'activationInfo',
                                    children: (
                                        <AtrcControlCheckbox
                                            label={__('Activation info', 'patterns-store')}
                                            checked={planContent.activationInfo}
                                            onChange={(newVal) => {
                                                const updatedSettings = AtrcNestedObjUpdateByKey2({
                                                    settings: pricing,
                                                    key1: 'planContent',
                                                    key2: 'activationInfo',
                                                    val2: newVal,
                                                });
                                                dbUpdateSetting('pricing', updatedSettings);
                                            }}
                                        />
                                    ),
                                },
                                {
                                    value: 'purchaseButton',
                                    children: (
                                        <AtrcControlCheckbox
                                            label={__('Purchase button', 'patterns-store')}
                                            checked={planContent.purchaseButton}
                                            onChange={(newVal) => {
                                                const updatedSettings = AtrcNestedObjUpdateByKey2({
                                                    settings: pricing,
                                                    key1: 'planContent',
                                                    key2: 'purchaseButton',
                                                    val2: newVal,
                                                });
                                                dbUpdateSetting('pricing', updatedSettings);
                                            }}
                                        />
                                    ),
                                },
                                {
                                    value: 'planDesc',
                                    children: (
                                        <AtrcControlCheckbox
                                            label={__('Plan description', 'patterns-store')}
                                            checked={planContent.planDesc}
                                            onChange={(newVal) => {
                                                const updatedSettings = AtrcNestedObjUpdateByKey2({
                                                    settings: pricing,
                                                    key1: 'planContent',
                                                    key2: 'planDesc',
                                                    val2: newVal,
                                                });
                                                dbUpdateSetting('pricing', updatedSettings);
                                            }}
                                        />
                                    ),
                                },
                                {
                                    value: 'licenseDesc',
                                    children: (
                                        <AtrcControlCheckbox
                                            label={__('License description', 'patterns-store')}
                                            checked={planContent.licenseDesc}
                                            onChange={(newVal) => {
                                                const updatedSettings = AtrcNestedObjUpdateByKey2({
                                                    settings: pricing,
                                                    key1: 'planContent',
                                                    key2: 'licenseDesc',
                                                    val2: newVal,
                                                });
                                                dbUpdateSetting('pricing', updatedSettings);
                                            }}
                                        />
                                    ),
                                },
                                {
                                    value: 'recurringInfo',
                                    children: (
                                        <AtrcControlCheckbox
                                            label={__('Recurring information', 'patterns-store')}
                                            checked={planContent.recurringInfo}
                                            onChange={(newVal) => {
                                                const updatedSettings = AtrcNestedObjUpdateByKey2({
                                                    settings: pricing,
                                                    key1: 'planContent',
                                                    key2: 'recurringInfo',
                                                    val2: newVal,
                                                });
                                                dbUpdateSetting('pricing', updatedSettings);
                                            }}
                                        />
                                    ),
                                },
                                {
                                    value: 'recurringDesc',
                                    children: (
                                        <AtrcControlCheckbox
                                            label={__('Recurring description', 'patterns-store')}
                                            checked={planContent.recurringDesc}
                                            onChange={(newVal) => {
                                                const updatedSettings = AtrcNestedObjUpdateByKey2({
                                                    settings: pricing,
                                                    key1: 'planContent',
                                                    key2: 'recurringDesc',
                                                    val2: newVal,
                                                });
                                                dbUpdateSetting('pricing', updatedSettings);
                                            }}
                                        />
                                    ),
                                },
                                {
                                    value: 'productTypeInfo',
                                    children: (
                                        <AtrcControlCheckbox
                                            label={__('Product type info', 'patterns-store')}
                                            checked={planContent.productTypeInfo}
                                            onChange={(newVal) => {
                                                const updatedSettings = AtrcNestedObjUpdateByKey2({
                                                    settings: pricing,
                                                    key1: 'planContent',
                                                    key2: 'productTypeInfo',
                                                    val2: newVal,
                                                });
                                                dbUpdateSetting('pricing', updatedSettings);
                                            }}
                                        />
                                    ),
                                },
                            ]}
                        />
                    </AtrcPanelRow>
                </AtrcPanelBody>
            </AtrcPanelRow>

            {onLicense ? (
                <AtrcPanelRow className={classNames('at-m')}>
                    <AtrcControlSelect
                        label={__('Display number of licenses filter', 'patterns-store')}
                        help={__('Number of license filter view', 'patterns-store')}
                        wrapProps={{
                            className: 'at-flx-grw-1',
                        }}
                        value={licView}
                        options={[
                            {
                                value: '',
                                label: __('Hide', 'patterns-store'),
                            },
                            {
                                value: 'select',
                                label: __('Select', 'patterns-store'),
                            },
                            {
                                value: 'button',
                                label: __('Button', 'patterns-store'),
                            },
                        ]}
                        onChange={(newVal) => {
                            const updatedSettings = AtrcNestedObjUpdateByKey1({
                                settings: pricing,
                                key1: 'licView',
                                val1: newVal,
                            });
                            dbUpdateSetting('pricing', updatedSettings);
                        }}
                    />
                </AtrcPanelRow>
            ) : null}

            {PatternsStoreLocalize.has_edd_recurring ? (
                <AtrcPanelRow className={classNames('at-m')}>
                    <AtrcControlSelect
                        label={__('Display recurring filter', 'patterns-store')}
                        help={__(
                            'Annual, lifetime or other recurring filter view',
                            'patterns-store'
                        )}
                        wrapProps={{
                            className: 'at-flx-grw-1',
                        }}
                        value={recView}
                        options={[
                            {
                                value: '',
                                label: __('Hide', 'patterns-store'),
                            },
                            {
                                value: 'select',
                                label: __('Select', 'patterns-store'),
                            },
                            {
                                value: 'button',
                                label: __('Button', 'patterns-store'),
                            },
                        ]}
                        onChange={(newVal) => {
                            const updatedSettings = AtrcNestedObjUpdateByKey1({
                                settings: pricing,
                                key1: 'recView',
                                val1: newVal,
                            });
                            dbUpdateSetting('pricing', updatedSettings);
                        }}
                    />
                </AtrcPanelRow>
            ) : null}
        </>
    );
};

const Documentation = () => {
    const data = useContext(AtrcReduxContextData);

    const { lsSettings, lsSaveSettings } = data;

    return (
        <AtrcWireFrameHeaderContentFooter
            headerRowProps={{
                className: classNames(AtrcPrefix('header-docs'), 'at-m'),
            }}
            renderHeader={
                <DocsTitle
                    onClick={() => {
                        const localStorageClone = cloneDeep(lsSettings);
                        localStorageClone.tplDocs1 = !localStorageClone.tplDocs1;
                        lsSaveSettings(localStorageClone);
                    }}
                />
            }
            renderContent={
                <>
                    <AtrcPanelBody
                        className={classNames(AtrcPrefix('m-0'))}
                        title={__('What do Pricing Popup settings do?', 'patterns-store')}
                        initialOpen={true}>
                        <AtrcText
                            tag='p'
                            className={classNames(AtrcPrefix('m-0'), 'at-m')}>
                            {__(
                                'Pricing Popup settings control the display of pricing plan options in a popup. These settings allow you to manage the overall configuration of the pricing popup, including the title, subtitle, pricing filter, content, and the overall pricing of the pattern. This ensures that users have a clear and organized view of the available pricing plans.',
                                'patterns-store'
                            )}
                        </AtrcText>
                    </AtrcPanelBody>
                </>
            }
            allowHeaderRow={false}
            allowHeaderCol={false}
            allowContentRow={false}
            allowContentCol={false}
        />
    );
};

const Settings = () => {
    const data = useContext(AtrcReduxContextData);
    const { dbIsLoading, dbCanSave, dbSettings, dbSaveSettings, lsSettings } =
        data;

    if (!dbSettings) {
        return null;
    }

    const { tplDocs1 } = lsSettings;

    return (
        <AtrcWireFrameHeaderContentFooter
            wrapProps={{
                className: classNames(AtrcPrefix('bg-white'), 'at-bg-cl'),
            }}
            renderHeader={
                <AtrcTitleTemplate2
                    title={__('Pricing popup settings', 'patterns-store')}
                    btnProps={{
                        className: classNames(
                            AtrcPrefix('btn-spin'),
                            'at-flx',
                            'at-al-itm-ctr',
                            'at-gap'
                        ),
                        onClick: () => dbSaveSettings(dbSettings),
                        isPrimary: true,
                        disabled: dbIsLoading || !dbCanSave,
                        children: (
                            <>
                                {dbCanSave
                                    ? __('Save settings', 'patterns-store')
                                    : __('Saved', 'patterns-store')}
                                {dbIsLoading ? <AtrcSpinner variant='inline' /> : ''}
                            </>
                        ),
                    }}
                />
            }
            renderContent={
                <AtrcWireFrameContentSidebar
                    wrapProps={{
                        allowContainer: true,
                        type: 'fluid',
                        tag: 'section',
                        className: 'at-p',
                    }}
                    renderContent={<MainContent />}
                    renderSidebar={!tplDocs1 ? <Documentation /> : null}
                    contentProps={{
                        contentCol: tplDocs1 ? 'at-col-12' : 'at-col-7',
                    }}
                    sidebarProps={{
                        sidebarCol: 'at-col-5',
                    }}
                />
            }
            allowHeaderRow={false}
            allowHeaderCol={false}
            allowContentRow={false}
            allowContentCol={false}
        />
    );
};
export default Settings;
