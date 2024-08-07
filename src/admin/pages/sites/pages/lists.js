/* WordPress */
import { __ } from '@wordpress/i18n';

import { useMemo, useContext } from '@wordpress/element';

/* Library */
import classNames from 'classnames';
import { BsTrash } from 'react-icons/bs';

/*Atrc*/
import {
    AtrcTd,
    AtrcTr,
    AtrcModalConfirm,
    AtrcWireFrameHeaderContentFooter,
    AtrcListFilterTemplate1,
    AtrcTable,
    AtrcPrefix,
} from 'atrc';

/*Inbuilt*/
import { Card } from '../organisms';
import { AtrcReduxContextData } from '../../../routes';

function isOdd(num) {
    return num % 2;
}

/* Local */
const Lists = (props) => {
    let {
        isLoading,
        queryArgs,
        items,
        countAllItems,
        countQueryItems,
        totalItems,
        totalPages,
        selectedItems,
        confirmDelete,
        setQueryArgs,
        refresh,
        toggleSelectAll,
        toggleSelect,
        setConfirmDelete,
        deleteItems,
        updateItem,
        insertItem,
        setItem,
    } = props;

    if (countAllItems === undefined) {
        return '';
    }

    const selectedIds = useMemo(() => {
        if (selectedItems && selectedItems.length > 0) {
            return selectedItems.map((itm) => itm.id);
        }
        return [];
    }, [selectedItems]);

    const bulkActions = (type) => {
        if ('delete' === type) {
            setConfirmDelete({
                ids: selectedIds,
                open: true,
            });
        }
    };

    /* local storage */
    const data = useContext(AtrcReduxContextData);
    const { lsSettings } = data;

    const { siteListDocs1 } = lsSettings;

    return (
        <>
            <AtrcModalConfirm
                title={
                    confirmDelete && confirmDelete.ids && confirmDelete.ids.length > 1
                        ? __('Delete the sites', 'patterns-store')
                        : __('Delete the site', 'patterns-store')
                }
                isOpen={confirmDelete && confirmDelete.open}
                onConfirm={() => deleteItems(confirmDelete.ids)}
                onCancel={() =>
                    setConfirmDelete({
                        ids: [],
                        open: false,
                    })
                }
            />

            <AtrcWireFrameHeaderContentFooter
                wrapProps={{
                    allowContainer: true,
                    type: 'fluid',
                    className: classNames(AtrcPrefix('bg-white'), 'at-bg-cl'),
                }}
                renderHeader={
                    <AtrcListFilterTemplate1
                        elements={[
                            'title',
                            'addNew',
                            'search',
                            'bulkCheck',
                            'sort',
                            'refresh',
                            'listGrid',
                            'pagination',
                        ]}
                        mid={{
                            left: ['title', 'addNew'],
                            right: ['search'],
                        }}
                        bottom={{
                            left: ['bulkCheck'],
                            right: ['pagination'],
                            hr: false,
                        }}
                        title={{
                            children: __('Sites', 'patterns-store'),
                        }}
                        addNew={{
                            to: 'create',
                            children: __('Add new', 'patterns-store'),
                            onClick: () => setItem({}),
                        }}
                        search={{
                            doSearch: (userInput) =>
                                setQueryArgs({ page: 1, search: userInput }),
                            value: queryArgs.search || '',
                        }}
                        bulkCheck={{
                            checkProps: {
                                checked:
                                    0 !== items.length && items.length === selectedItems.length,
                                onChange: () => toggleSelectAll(),
                            },
                            showBulkActions: selectedItems.length,
                            actions: [
                                {
                                    buttonProps: {
                                        onClick: () => bulkActions('delete'),
                                    },
                                    tooltipProps: {
                                        text: __('Delete permanently', 'patterns-store'),
                                    },
                                    iconProps: {
                                        type: 'bootstrap',
                                        icon: BsTrash,
                                    },
                                },
                            ],
                            toggleItems: ['order', 'refresh'],
                            checkedIds: selectedIds,
                        }}
                        order={{
                            selectProps: {
                                value: queryArgs.orderby,
                                options: [
                                    { label: __('Date', 'patterns-store'), value: 'created_at' },
                                    { label: __('Title', 'patterns-store'), value: 'title' },
                                ],
                                onChange: (orderby) => setQueryArgs({ orderby }),
                            },
                            buttonProps: {
                                onClick: () => {
                                    setQueryArgs({
                                        order: 'asc' === queryArgs.order ? 'desc' : 'asc',
                                    });
                                },
                            },
                            order: queryArgs.order,
                        }}
                        refresh={{
                            buttonProps: {
                                onClick: () => refresh({ page: 1 }),
                            },
                            isPending: isLoading,
                        }}
                        pagination={{
                            currentPage: parseInt(queryArgs.page) || 1,
                            totalPages: parseInt(totalPages),
                            doPagination: (newPage) => setQueryArgs({ page: newPage }),
                            totalItems: parseInt(countQueryItems),
                        }}
                    />
                }
                renderContent={
                    <AtrcTable
                        tHeadLabels={[
                            '__blank',
                            __('Title', 'patterns-store'),
                            __('Url', 'patterns-store'),
                            __('Author', 'patterns-store'),
                            __('Version', 'patterns-store'),
                            __('Status', 'patterns-store'),
                            __('Date', 'patterns-store'),
                            __('Actions', 'patterns-store'),
                        ]}
                        renderTbody={
                            items.length ? (
                                items.map(function (itm, index) {
                                    return (
                                        <Card
                                            item={itm}
                                            key={index}
                                            setConfirmDelete={setConfirmDelete}
                                            toggleSelect={toggleSelect}
                                            isOdd={isOdd(index + 1)}
                                            selectedIds={selectedIds}
                                            updateItem={updateItem}
                                        />
                                    );
                                })
                            ) : (
                                <AtrcTr
                                    className={classNames('at-bdr')}
                                    isEven>
                                    <AtrcTd
                                        className={classNames('at-p')}
                                        colSpan='8'>
                                        {__('No sites found.', 'patterns-store')}
                                    </AtrcTd>
                                </AtrcTr>
                            )
                        }
                    />
                }
                renderFooter={
                    <AtrcListFilterTemplate1
                        elements={['pagination']}
                        bottom={{
                            right: ['pagination'],
                            hr: false,
                        }}
                        pagination={{
                            currentPage: parseInt(queryArgs.page) || 1,
                            totalPages: parseInt(totalPages),
                            doPagination: (newPage) => setQueryArgs({ page: newPage }),
                            totalItems: parseInt(countQueryItems),
                            isFooter: true,
                        }}
                    />
                }
                allowHeaderRow={false}
                allowHeaderCol={false}
                allowContentRow={false}
                allowContentCol={false}
                allowFooterRow={false}
                allowFooterCol={false}
            />
        </>
    );
};

export default Lists;
