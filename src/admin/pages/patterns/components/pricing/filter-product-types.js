/* WordPress */
import { useMemo, useEffect } from '@wordpress/element';

/* Atrc */
import {
	AtrcButton,
	AtrcButtonGroup,
	AtrcControlSelect,
	AtrcTooltip,
} from 'atrc';

/* Local */
export const getCurrentPlanInfo = (type) => {
	const { pricingData } = PatternsStoreLocalize;
	if ('pattern' === type) {
		return pricingData.patternPlan;
	}
	if ('pattern-kit' === type) {
		return pricingData.patternKitPlan;
	}
	if ('all-access' === type) {
		return pricingData.allAccessPlan;
	}
	return null;
};

const FilterProductTypes = ({ availableValues, setFilteValue, value }) => {
	const { pricingData, currentPattternId } = PatternsStoreLocalize;
	const { allAccess } = pricingData;

	/* if current product is all-access */
	useEffect(() => {
		if (allAccess === currentPattternId) {
			setFilteValue('all-access');
		}
	}, [allAccess, currentPattternId]);

	/**
	 * Memoized options for a dropdown based on available values and their current plans.
	 * @returns {Array} An array of options with value and label properties.
	 */
	const options = useMemo(() => {
		const newOptions = [];
		availableValues.forEach((item) => {
			const currentItem = getCurrentPlanInfo(item);
			const label =
				currentItem && currentItem.filterLabel ? currentItem.filterLabel : item;
			const desc =
				currentItem && currentItem.filterDesc ? currentItem.filterDesc : '';

			newOptions.push({ value: item, label, desc });
		});
		return newOptions;
	}, [availableValues]);

	const { planView } = pricingData;

	/* if current product is all-access */
	if (allAccess === currentPattternId) {
		return null;
	}

	if ('button' === planView.view) {
		return (
			<AtrcButtonGroup className='ps-plan__btn-grp at-jfy-cont-ctr'>
				{options.map((item) => (
					<AtrcButton
						isActive={value === item.value}
						variant='light'
						onClick={() => setFilteValue(item.value)}>
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

export default FilterProductTypes;
