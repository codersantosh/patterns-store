/*CSS*/
import './editor.scss';

/* WordPress */
import { __ } from '@wordpress/i18n';

import { registerPlugin } from '@wordpress/plugins';
import { PluginDocumentSettingPanel } from '@wordpress/editor';
import { useSelect } from '@wordpress/data';
import { useEffect, useState, createContext } from '@wordpress/element';

/* Library */
import classNames from 'classnames';

/*Atrc*/
import { AtrcApis } from 'atrc/build/data';
import { AtrcApplyWithSettings } from 'atrc/build/data';

import {
    AtrcButton,
    AtrcControlText,
    AtrcControlTextarea,
    AtrcNotice,
    AtrcPanelRow,
    AtrcSpinner,
} from 'atrc';

/* Inbuilt */
export const AtrcReduxContextEditData = createContext();

import ModelThemeJson from './theme-json';

/* Local */
const PatternsStoreAddPatternsMeta = ({
    lsSettings,
    lsUpdateSetting,
    lsSaveSettings,
}) => {
    const [metaData, setMetaData] = useState({});
    const [timeoutId, setTimeoutId] = useState(null);
    const [notice, setNotice] = useState({});
    const [isSaving, setIsSaving] = useState(false);
    const [isOpenThemeJson, setIsOpenThemeJson] = useState(false);

    const postId = useSelect((select) =>
        select('core/editor').getCurrentPostId()
    );

    const getMetaData = async () => {
        try {
            const getData = await AtrcApis.doApi({
                key: 'patternMeta',
                type: 'getItem',
                rowId: postId,
            });
            setMetaData(getData);
        } catch (error) {
            console.error(error);
        }
    };

    useEffect(() => {
        if (postId) {
            getMetaData();
        }
    }, [postId]);

    const saveMetaData = async (updatedData) => {
        setIsSaving(true);
        const { id, ...dataWithoutId } = updatedData;

        try {
            const response = await AtrcApis.doApi({
                key: 'patternMeta',
                type: 'updateItem',
                rowId: postId,
                data: dataWithoutId,
            });

            if (response.code) {
                setNotice({
                    status: 'error',
                    message:
                        __('Error saving metadata:', 'patterns-store') + response.message,
                });
            } else {
                setNotice({
                    status: 'success',
                    message: __('Saved metadata', 'patterns-store'),
                });
            }
        } catch (error) {
            setNotice({
                status: 'error',
                message: __('Error saving metadata:', 'patterns-store') + error.message,
            });
            console.error(error);
        } finally {
            setIsSaving(false);
        }
    };

    const debouncedSave = (updatedData) => {
        if (timeoutId) {
            clearTimeout(timeoutId);
        }
        const newTimeoutId = setTimeout(() => {
            saveMetaData(updatedData); // Call saveMetaData after 2 seconds
        }, 2000);
        setTimeoutId(newTimeoutId);
    };

    const handleInputChange = (newValue, key) => {
        const newMetaData = {
            ...metaData,
            [key]: newValue,
        };
        setMetaData(newMetaData);
        debouncedSave(newMetaData);
    };

    if (!postId) {
        return null;
    }

    const dbProps = {
        setIsOpenThemeJson: setIsOpenThemeJson,
        postId: postId,
        isOpenThemeJson: isOpenThemeJson,
        lsSettings: lsSettings,
        lsUpdateSetting: lsUpdateSetting,
        lsSaveSettings: lsSaveSettings,
    };

    return (
        <PluginDocumentSettingPanel
            className={classNames('ps-edit-pnl')}
            name='patterns-store-pattern-meta'
            title={__('Patterns metadata', 'patterns-store')}>
            {isSaving || (notice && notice.message) ? (
                <AtrcPanelRow
                    className={classNames(
                        'at-m',
                        'at-flx-col',
                        'at-al-itm-st',
                        'at-pos',
                        'ps-notice'
                    )}>
                    {isSaving ? <AtrcSpinner /> : null}
                    {notice && notice.message ? (
                        <AtrcNotice
                            onRemove={() => setNotice('')}
                            onAutoRemove={() => setNotice('')}
                            status={notice.status}>
                            {notice.message}
                        </AtrcNotice>
                    ) : null}
                </AtrcPanelRow>
            ) : null}

            <AtrcPanelRow className={classNames('at-m')}>
                <AtrcButton
                    onClick={() => {
                        setIsOpenThemeJson(true);
                    }}>
                    {__('Upload new theme.json package', 'patterns-store')}
                </AtrcButton>
                <AtrcReduxContextEditData.Provider value={{ ...dbProps }}>
                    <ModelThemeJson />
                </AtrcReduxContextEditData.Provider>
            </AtrcPanelRow>

            <AtrcPanelRow className={classNames('at-m')}>
                <AtrcControlTextarea
                    allowReset={false}
                    label={__('Image sources used in pattern(if any)', 'patterns-store')}
                    value={metaData.image_sources}
                    onChange={(newVal) => handleInputChange(newVal, 'image_sources')}
                />
            </AtrcPanelRow>
            <AtrcPanelRow className={classNames('at-m')}>
                <AtrcControlTextarea
                    allowReset={false}
                    label={__('Contains block types', 'patterns-store')}
                    value={metaData.contains_block_types}
                    readOnly
                />
            </AtrcPanelRow>
            <AtrcPanelRow className={classNames('at-m')}>
                <AtrcControlTextarea
                    allowReset={false}
                    label={__('Footnotes', 'patterns-store')}
                    value={metaData.footnotes}
                    onChange={(newVal) => handleInputChange(newVal, 'footnotes')}
                />
            </AtrcPanelRow>
            <AtrcPanelRow className={classNames('at-m')}>
                <AtrcControlText
                    allowReset={false}
                    label={__('Demo URL', 'patterns-store')}
                    help={__('Demo URL will replace the current preview url of the pattern.', 'patterns-store')}
                    value={metaData.demo_url}
                    onChange={(newVal) => handleInputChange(newVal, 'demo_url')}
                />
            </AtrcPanelRow>
            <AtrcPanelRow className={classNames('at-m')}>
                <AtrcControlText
                    allowReset={false}
                    label={__('Viewport width', 'patterns-store')}
                    value={metaData.viewport_width}
                    onChange={(newVal) => handleInputChange(newVal, 'viewport_width')}
                />
            </AtrcPanelRow>
            <AtrcPanelRow className={classNames('at-m')}>
                <AtrcControlText
                    allowReset={false}
                    label={__('WP locale', 'patterns-store')}
                    value={metaData.wp_locale}
                    onChange={(newVal) => handleInputChange(newVal, 'wp_locale')}
                />
            </AtrcPanelRow>
            <AtrcPanelRow className={classNames('at-m')}>
                <AtrcControlText
                    allowReset={false}
                    label={__('WP version', 'patterns-store')}
                    value={metaData.wp_version}
                    onChange={(newVal) => handleInputChange(newVal, 'wp_version')}
                />
            </AtrcPanelRow>
        </PluginDocumentSettingPanel>
    );
};

const InitLocalStorageSettings = (props) => {
    const { settings, updateSetting, saveSettings } = props;
    const defaultSettings = {
        tjDocs1: true /* theme json 1 */,
    };
    const dbProps = {
        lsSettings: settings || defaultSettings,
        lsUpdateSetting: updateSetting,
        lsSaveSettings: saveSettings,
    };

    return <PatternsStoreAddPatternsMeta {...dbProps} />;
};
const InitLocalStorageSettingsWithHoc = AtrcApplyWithSettings(
    InitLocalStorageSettings
);

const InitLocalStorage = () => {
    return (
        <InitLocalStorageSettingsWithHoc
            atrcStore='patterns-store'
            atrcStoreKey='patternsStoreLocal'
        />
    );
};

registerPlugin('patterns-store-pattern-meta', {
    render: InitLocalStorage,
});
