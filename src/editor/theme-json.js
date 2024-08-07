/* WordPress */
import { __ } from '@wordpress/i18n';
import { useState, useEffect, useContext } from '@wordpress/element';
import { FormFileUpload, DropZone } from '@wordpress/components';

/* Library */
import classNames from 'classnames';
import { cloneDeep } from 'lodash';

/*Atrc*/
import { AtrcApis } from 'atrc/build/data';

import {
    AtrcWireFrameHeaderContentFooter,
    AtrcWireFrameSidebarContentSidebar,
    AtrcModal,
    AtrcSpinner,
    AtrcPrefix,
    AtrcWrap,
    AtrcText,
    AtrcFileTreeTemplate1,
    AtrcNotice,
    AtrcList,
    AtrcLi,
    AtrcPanelBody,
    AtrcButton,
} from 'atrc';

/* Inbuilt */
import { AtrcReduxContextEditData } from './index.js';
import DocsTitle from './../admin/components/molecules/docs-title';

/* Local */
const sizeFormat = (size) => {
    if (size < 1024) {
        return size + ' B';
    } else if (size < 1048576) {
        return (size / 1024).toFixed(2) + ' KB';
    } else if (size < 1073741824) {
        return (size / 1048576).toFixed(2) + ' MB';
    } else {
        return (size / 1073741824).toFixed(2) + ' GB';
    }
};

const Documentation = () => {
    const { themeJsonAllowed } = PatternsStoreLocalize;
    const exampleFilePaths = {
        0: {
            name: 'theme.json',
            url: '#',
        },
        assets: {
            fonts: [
                {
                    name: 'roboto_italic_100.ttf',
                    url: '#',
                },
                {
                    name: 'roboto_italic_300.ttf',
                    url: '#',
                },
            ],
        },
    };
    const data = useContext(AtrcReduxContextEditData);

    const { lsSettings, lsSaveSettings } = data;

    return (
        <AtrcWireFrameHeaderContentFooter
            headerRowProps={{
                className: classNames(AtrcPrefix('header-docs'), 'at-m'),
            }}
            renderHeader={
                <DocsTitle
                    title={__('Theme JSON package guidelines', 'patterns-store')}
                    onClick={() => {
                        const localStorageClone = cloneDeep(lsSettings);
                        localStorageClone.tjDocs1 = !localStorageClone.tjDocs1;
                        lsSaveSettings(localStorageClone);
                    }}
                />
            }
            renderContent={
                <AtrcWrap className={classNames('ps-theme-json-modal-main')}>
                    <AtrcText>
                        {__(
                            'To ensure smooth processing of your theme JSON package zip file, please follow these guidelines:',
                            'patterns-store'
                        )}
                    </AtrcText>

                    <AtrcList className={classNames('ps-file-type-info', 'at-m')}>
                        <AtrcLi>
                            <AtrcText tag='span'>
                                {__('Allowed file extensions : ', 'patterns-store')}
                                <AtrcWrap
                                    tag='span'
                                    className={classNames('ps-allowed-types')}>
                                    {themeJsonAllowed.extensions.join(', ')}
                                </AtrcWrap>
                            </AtrcText>
                        </AtrcLi>
                        <AtrcLi>
                            <AtrcText tag='span'>
                                {__('Allowed directories : ', 'patterns-store')}
                                <AtrcWrap
                                    tag='span'
                                    className={classNames('ps-allowed-types')}>
                                    {themeJsonAllowed.directories.join(', ')}
                                </AtrcWrap>
                            </AtrcText>
                        </AtrcLi>
                    </AtrcList>

                    <AtrcPanelBody
                        title={__('Example folder structure : ', 'patterns-store')}
                        initialOpen={true}>
                        <AtrcFileTreeTemplate1 value={exampleFilePaths} />
                    </AtrcPanelBody>
                </AtrcWrap>
            }
            allowHeaderRow={false}
            allowHeaderCol={false}
            allowContentRow={false}
            allowContentCol={false}
        />
    );
};

const ModelThemeJson = () => {
    const { maxUploadSize } = PatternsStoreLocalize;

    const data = useContext(AtrcReduxContextEditData);
    const {
        postId,
        setIsOpenThemeJson,
        isOpenThemeJson,
        lsSettings,
        lsSaveSettings,
    } = data;

    const { tjDocs1 } = lsSettings;

    const [isLoading, setIsLoading] = useState(true);

    const [notice, setNotice] = useState({ status: '', message: '' });
    const [themeJsonData, setThemeJsonData] = useState();
    const [uploading, setUploading] = useState(false);

    const getThemeJsonPackage = async () => {
        setIsLoading(true);

        try {
            const getData = await AtrcApis.doApi({
                key: 'patternCustomThemeJson',
                type: 'getItem',
                rowId: postId,
            });

            if (getData.error) {
                setNotice({
                    status: 'error',
                    message: getData.error.response.data.message,
                });
            } else {
                setThemeJsonData(getData.themeJsonPackage);
            }
        } catch (error) {
            console.error(error);
            setNotice({
                status: 'error',
                message: error.message,
            });
        } finally {
            setIsLoading(false);
        }
    };

    const uploadThemeJsonPackage = async (files) => {
        const file = files[0];

        if (!file) {
            setNotice({
                status: 'error',
                message: __('No files selected.', 'patterns-store'),
            });
            return;
        }

        const validMimeTypes = [
            'application/zip',
            'application/x-zip-compressed',
            'multipart/x-zip',
            'application/x-compressed',
        ];
        if (!validMimeTypes.includes(file.type)) {
            setNotice({
                status: 'error',
                message: __('Please upload a valid zip file.', 'patterns-store'),
            });
            return;
        }

        setUploading(true);

        const formData = new FormData();
        formData.append('themeJsonPackage', file);

        try {
            const getData = await AtrcApis.doApi({
                key: 'patternCustomThemeJson',
                type: 'updateItem',
                rowId: postId,
                data: formData,
            });

            if (getData.error) {
                setNotice({
                    status: 'error',
                    message: getData.error.response.data.message,
                });
            } else {
                setThemeJsonData(getData.themeJsonPackage);
                setNotice({
                    status: 'success',
                    message: __('Theme JSON package uploaded successfully.', 'patterns-store'),
                });
            }
        } catch (error) {
            console.error(error);
            setNotice({
                status: 'error',
                message: error.message,
            });
        } finally {
            setUploading(false);
        }
    };

    const deleteThemeJsonPackage = async () => {
        setUploading(true);

        try {
            const deleteData = await AtrcApis.doApi({
                key: 'patternCustomThemeJson',
                type: 'deleteItem',
                rowId: postId,
            });

            if (deleteData.error) {
                setNotice({
                    status: 'error',
                    message: deleteData.error.response.data.message,
                });
            } else {
                setThemeJsonData(null);
                setNotice({
                    status: 'success',
                    message: __('Theme JSON package deleted successfully.', 'patterns-store'),
                });
            }
        } catch (error) {
            console.error(error);
            setNotice({
                status: 'error',
                message: error.message,
            });
        } finally {
            setUploading(false);
        }
    };

    useEffect(() => {
        if (postId) {
            getThemeJsonPackage();
        }
    }, [postId]);

    if (!isOpenThemeJson) {
        return null;
    }

    return (
        <AtrcModal
            className={classNames('ps-theme-json-modal')}
            bodyOpenClassName='ps-modal__open'
            onRequestClose={() => setIsOpenThemeJson(false)}
            title={__('Theme.json package', 'patterns-store')}
            isFullScreen={true}>
            {isLoading ? (
                <AtrcSpinner />
            ) : (
                <AtrcWireFrameSidebarContentSidebar
                    wrapProps={{
                        allowContainer: true,
                        type: 'fluid',
                        tag: 'section',
                        className: 'at-p',
                    }}
                    renderContent={
                        <>
                            {notice && notice.status ? (
                                <AtrcNotice
                                    status={notice.status}
                                    isDismissible={false}>
                                    <AtrcText className={classNames('at-m')}>
                                        {notice.message}
                                    </AtrcText>
                                </AtrcNotice>
                            ) : null}
                            {themeJsonData && Object.keys(themeJsonData).length > 0 ? (
                                <AtrcButton
                                    variant='danger'
                                    onClick={() => deleteThemeJsonPackage()}>
                                    {__('Delete', 'patterns-store')}
                                </AtrcButton>
                            ) : null}

                            <AtrcWrap
                                className={classNames(
                                    'at-flx',
                                    'at-flx-col',
                                    'at-al-itm-ctr',
                                    'at-jfy-cont-ctr',
                                    'ps-file-uploader',
                                    'at-bdr',
                                    'at-p',
                                    'at-m',
                                    'at-pos'
                                )}>
                                <DropZone
                                    onFilesDrop={(files) => uploadThemeJsonPackage(files)}
                                />

                                <FormFileUpload
                                    isPrimary
                                    accept='.zip'
                                    onChange={(e) => {
                                        uploadThemeJsonPackage(e.target.files);
                                        e.target.value = '';
                                    }}>
                                    {__('Upload new theme.json package', 'patterns-store')}
                                </FormFileUpload>
                                <AtrcText className={classNames('ps-file-upload-size', 'at-m')}>
                                    {__('Maximum upload file size:', 'patterns-store')}
                                    {sizeFormat(maxUploadSize)}
                                </AtrcText>
                                {uploading ? <AtrcSpinner variant='inline' /> : null}
                            </AtrcWrap>
                            <AtrcWrap className={classNames('ps-file-upload-info')}>
                                <AtrcText className={classNames('at-m')}>
                                    {__(
                                        'Please make sure that your zip file adheres to these guidelines. Any files or directories outside of these specifications may lead to processing errors.',
                                        'patterns-store'
                                    )}
                                    <AtrcButton
                                        variant=''
                                        onClick={() => {
                                            const localStorageClone = cloneDeep(lsSettings);
                                            localStorageClone.tjDocs1 = !localStorageClone.tjDocs1;
                                            lsSaveSettings(localStorageClone);
                                        }}>
                                        &nbsp;
                                        {__('Documentation', 'patterns-store')}
                                    </AtrcButton>
                                </AtrcText>
                            </AtrcWrap>
                        </>
                    }
                    renderSidebarLeft={
                        <AtrcWrap
                            className={classNames(
                                'ps-theme-json-modal-sidebar',
                                'at-p',
                                'at-bdr',
                                'at-h'
                            )}>
                            <>
                                {themeJsonData && Object.keys(themeJsonData).length > 0 ? (
                                    <>
                                        <AtrcFileTreeTemplate1 value={themeJsonData} />
                                    </>
                                ) : (
                                    <AtrcText
                                        tag='h6'
                                        className={classNames('at-m', 'ps-tree-ttl')}>
                                        {__('No theme.json package found!', 'patterns-store')}
                                    </AtrcText>
                                )}
                            </>
                        </AtrcWrap>
                    }
                    renderSidebarRight={!tjDocs1 ? <Documentation /> : null}
                    columns={{
                        left: 'at-col-3',
                        content: tjDocs1 ? 'at-col-9' : 'at-col-6',
                        right: 'at-col-3',
                    }}
                />
            )}
        </AtrcModal>
    );
};

export default ModelThemeJson;
