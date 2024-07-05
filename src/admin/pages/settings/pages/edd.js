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
    AtrcControlToggle,
} from 'atrc';

/* Inbuilt */
import { AtrcReduxContextData } from '../../../routes';
import DocsTitle from '../../../components/molecules/docs-title';

/*Local*/
const MainContent = () => {
    const data = useContext(AtrcReduxContextData);

    const { dbSettings, dbUpdateSetting } = data;

    const { products = {} } = dbSettings;
    const { offRename = false, offDirect = false, offEddApi = false } = products;

    const updateSettingKey = (key, val) => {
        const settingCloned = cloneDeep(products);
        settingCloned[key] = val;
        dbUpdateSetting('products', settingCloned);
    };

    return (
        <>
            <AtrcPanelRow className={classNames('at-m')}>
                <AtrcControlToggle
                    label={__('Disable "Download/s" Renaming', 'patterns-store')}
                    help={__(
                        'Disabling this option will keep the default labels for "Pattern" and "Patterns" as "Download" and "Downloads" throughout your website.',
                        'patterns-store'
                    )}
                    checked={offRename}
                    onChange={() => updateSettingKey('offRename', !offRename)}
                />
            </AtrcPanelRow>
            {PatternsStoreLocalize.has_pro ? <>
                <AtrcPanelRow className={classNames('at-m')}>
                    <AtrcControlToggle
                        label={__(
                            'Disable default "Buy Now(direct)" behavior for cart buttons',
                            'patterns-store'
                        )}
                        help={__(
                            'By default, all items\' cart buttons act as "Buy Now" or "direct". If you disable this option, they will function as normal Easy Digital Downloads (EDD) purchase buttons.',
                            'patterns-store'
                        )}
                        checked={offDirect}
                        onChange={() => updateSettingKey('offDirect', !offDirect)}
                    />
                </AtrcPanelRow>

                <AtrcPanelRow className={classNames('at-m')}>
                    <AtrcControlToggle
                        label={__('Disable Default EDD API', 'patterns-store')}
                        help={__(
                            'Prevent exposure of EDD product content from the default EDD API to the public.',
                            'patterns-store'
                        )}
                        checked={offEddApi}
                        onChange={() => updateSettingKey('offEddApi', !offEddApi)}
                    />
                </AtrcPanelRow>
            </> : null}
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
                        localStorageClone.eddDocs1 = !localStorageClone.eddDocs1;
                        lsSaveSettings(localStorageClone);
                    }}
                />
            }
            renderContent={
                <AtrcPanelBody
                    className={classNames(AtrcPrefix('m-0'))}
                    title={__(
                        'What is the purpose of using EDD in patterns?',
                        'patterns-store'
                    )}
                    initialOpen={true}>
                    <AtrcText tag='p'>
                        {__(
                            'The primary purpose of using EDD is to facilitate the sale of patterns and pattern kits, although you can also offer them for free. By leveraging all the features of EDD, and with the patterns store plugin, you gain the ability to sell patterns. You can add pattern designs to each download post type.',
                            'patterns-store'
                        )}
                    </AtrcText>
                </AtrcPanelBody>
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

    const { eddDocs1 } = lsSettings;

    return (
        <AtrcWireFrameHeaderContentFooter
            wrapProps={{
                className: classNames(AtrcPrefix('bg-white'), 'at-bg-cl'),
            }}
            renderHeader={
                <AtrcTitleTemplate2
                    title={__('EDD settings', 'patterns-store')}
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
                    renderSidebar={!eddDocs1 ? <Documentation /> : null}
                    contentProps={{
                        contentCol: eddDocs1 ? 'at-col-12' : 'at-col-7',
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
