/* WordPress */
import { useMemo } from '@wordpress/element';

/* Atrc */
import {
    AtrcButton,
    AtrcButtonGroup,
    AtrcControlSelect,
    AtrcTooltip,
} from 'atrc';

/* Local */
export const getCurrentRecurringInfo = (id) => {
    const recurring = PatternsStoreLocalize.pricingData.recurring.details;

    // Find the recurring object with the specified id
    const foundItem = recurring.find((item) => item.id === id);

    // If a recurring with the given id is found, return it; otherwise, return null
    return foundItem || null;
};

const FilterRecurring = ({ availableValues, setFilteValue, value }) => {
    const { pricingData } = PatternsStoreLocalize;

    /**
     * Memoized options for a dropdown based on available values and their current plans.
     * @returns {Array} An array of options with value and label properties.
     */
    const options = useMemo(() => {
        const newOptions = [];
        availableValues.forEach((item) => {
            const currentItem = getCurrentRecurringInfo(item);

            const label =
                currentItem && currentItem.filterLabel ? currentItem.filterLabel : item;

            const desc =
                currentItem && currentItem.filterDesc ? currentItem.filterDesc : '';

            newOptions.push({ value: item, label, desc });
        });
        return newOptions;
    }, [availableValues]);

    const { recView } = pricingData;

    if (!options) {
        return null;
    }

    if ('button' === recView) {
        return (
            <AtrcButtonGroup className='ps-plan__btn-grp at-jfy-cont-ctr'>
                {options.map((item, itemIdx) => (
                    <AtrcButton
                        key={itemIdx}
                        isActive={value === item.value}
                        onClick={() => setFilteValue(item.value)}
                        variant='light'>
                        {item.desc ? (
                            <AtrcTooltip
                                placement='top'
                                delay={0}
                                text={item.desc}>
                                {item.label}
                            </AtrcTooltip>
                        ) : (
                            item.label
                        )}
                    </AtrcButton>
                ))}
            </AtrcButtonGroup>
        );
    }
    return (
        <AtrcControlSelect
            allowReset={false}
            options={options}
            onChange={(newVal) => {
                setFilteValue(newVal);
            }}
        />
    );
};

export default FilterRecurring;
