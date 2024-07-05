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
export const getCurrentLicenseInfo = (limit) => {
	const { pricingData } = PatternsStoreLocalize;
	const { license } = pricingData;

	// Find the license object with the specified limit
	const foundLicense = license.find((item) => item.limit === limit);

	// If a license with the given limit is found, return it; otherwise, return null
	return foundLicense || null;
};

const FilterLicense = ({ availableValues, setFilteValue, value }) => {
	const { pricingData } = PatternsStoreLocalize;

	/**
	 * Memoized options for a dropdown based on available values and their current plans.
	 * @returns {Array} An array of options with value and label properties.
	 */
	const options = useMemo(() => {
		const newOptions = [];
		availableValues.forEach((item) => {
			const currentItem = getCurrentLicenseInfo(item);
			const label =
				currentItem && currentItem.filterLabel ? currentItem.filterLabel : item;
			const desc =
				currentItem && currentItem.filterDesc ? currentItem.filterDesc : '';

			newOptions.push({ value: item, label, desc });
		});
		return newOptions;
	}, [availableValues]);

	if (!options) {
		return null;
	}

	const { licView } = pricingData;

	if ('button' === licView) {
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
			wrapProps={{
				className: '',
			}}
			allowReset={false}
			options={options}
			onChange={(newVal) => {
				setFilteValue(newVal);
			}}
		/>
	);
};

export default FilterLicense;
