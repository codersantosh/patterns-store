/* WordPress */
import { __ } from '@wordpress/i18n';
import { useEffect, useState } from '@wordpress/element';

/* Library */
import { BsPencilFill, BsTrash, BsThreeDots } from 'react-icons/bs';
import { cloneDeep } from 'lodash';

/*Atrc*/
import {
    AtrcButton,
    AtrcButtonGroup,
    AtrcControlCheckbox,
    AtrcIcon,
    AtrcLink,
    AtrcTd,
    AtrcTr,
    AtrcText,
    AtrcTooltip,
    AtrcImgAndTextTemplate1,
    AtrcDropdown,
    AtrcMenuGroup,
    AtrcMenuItemsChoice,
    AtrcBadge,
} from 'atrc';

/*Local*/
const SiteCard = ({
    item,
    setConfirmDelete,
    toggleSelect,
    isOdd,
    selectedIds,
    updateItem,
}) => {
    const [isChecked, setChecked] = useState(false);

    useEffect(() => {
        if (selectedIds.includes(item.id)) {
            setChecked(true);
        } else {
            setChecked(false);
        }
    }, [selectedIds]);

    return (
        <AtrcTr
            isOdd={isOdd}
            isEven={!isOdd}>
            <AtrcTd className={'at-p'}>
                <AtrcControlCheckbox
                    checked={isChecked}
                    onChange={() => toggleSelect(item)}
                />
            </AtrcTd>
            <AtrcTd className={'at-p'}>
                <AtrcLink
                    variant='site'
                    type='router-link'
                    to={`edit/${item.id}`}>
                    <AtrcText tag='span'>
                        {item.title || __('…', 'patterns-store')}
                    </AtrcText>
                </AtrcLink>
            </AtrcTd>
            <AtrcTd className={'at-p'}>
                <AtrcText tag='span'>{item.url || __('…', 'patterns-store')}</AtrcText>
            </AtrcTd>
            <AtrcTd className={'at-p'}>
                <AtrcImgAndTextTemplate1
                    imgProps={{
                        src: item['author_details'].img,
                    }}
                    wordProps={{
                        children: item['author_details'].name,
                    }}
                />
            </AtrcTd>
            <AtrcTd className={'at-p'}>
                <AtrcText tag='span'>
                    {item.plugin_version || __('…', 'patterns-store')}
                </AtrcText>
            </AtrcTd>
            <AtrcTd className={'at-p'}>
                <AtrcBadge
                    variant={'active' === item.status ? 'secondary' : 'tertiary'}
                    childVariant={'active' === item.status ? 'dark' : ''}>
                    {item.status || __('…', 'patterns-store')}
                </AtrcBadge>
            </AtrcTd>
            <AtrcTd className={'at-p'}>
                <AtrcTooltip text={item['created_details'].date}>
                    {item['created_details'].ago}
                </AtrcTooltip>
            </AtrcTd>
            <AtrcTd className={'at-p'}>
                <AtrcButtonGroup>
                    <AtrcButton
                        variant='light'
                        onClick={() => {
                            setConfirmDelete({
                                ids: [item.id],
                                open: true,
                            });
                        }}>
                        <AtrcTooltip text={__('Delete', 'patterns-store')}>
                            <AtrcIcon
                                type='bootstrap'
                                icon={BsTrash}
                            />
                        </AtrcTooltip>
                    </AtrcButton>
                    <AtrcButton
                        variant='light'
                        isLink
                        type='router-link'
                        to={`edit/${item.id}`}>
                        <AtrcTooltip text={__('Edit', 'patterns-store')}>
                            <AtrcIcon
                                type='bootstrap'
                                icon={BsPencilFill}
                            />
                        </AtrcTooltip>
                    </AtrcButton>

                    <AtrcDropdown
                        popoverProps={{ placement: 'bottom-start' }}
                        renderToggle={({ isOpen, onToggle }) => (
                            <AtrcButton
                                onClick={onToggle}
                                variant='light'>
                                <AtrcIcon
                                    type='bootstrap'
                                    icon={BsThreeDots}
                                />
                            </AtrcButton>
                        )}
                        renderContent={({ onToggle }) => (
                            <AtrcMenuGroup label={__('Change Status', 'patterns-store')}>
                                <AtrcMenuItemsChoice
                                    value={item.status}
                                    choices={[
                                        {
                                            value: 'active',
                                            label: __('Active', 'patterns-store'),
                                            info: __('Activate the site.', 'patterns-store'),
                                        },
                                        {
                                            value: 'inactive',
                                            label: __('Inactive', 'patterns-store'),
                                            info: __('Mark as inactive.', 'patterns-store'),
                                        },
                                        {
                                            value: 'denied',
                                            label: __('Access denied', 'patterns-store'),
                                            info: __('Access denied to the site.', 'patterns-store'),
                                        },
                                        {
                                            value: 'flag',
                                            label: __('Flag', 'patterns-store'),
                                            info: __('Mark as flagged.', 'patterns-store'),
                                        },
                                    ]}
                                    onSelect={(newValue) => {
                                        const itemCloned = cloneDeep(item);
                                        itemCloned.status = newValue;
                                        updateItem(itemCloned);
                                    }}
                                />
                            </AtrcMenuGroup>
                        )}
                    />
                </AtrcButtonGroup>
            </AtrcTd>
        </AtrcTr>
    );
};

export default SiteCard;
