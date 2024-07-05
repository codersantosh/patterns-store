/* WordPress */
import { __ } from '@wordpress/i18n';

/* Atrc */
import {
	AtrcHr,
	AtrcLi,
	AtrcControlToggle,
	AtrcList,
	AtrcText,
	AtrcWrap,
} from 'atrc';

/* Local */
const SelectCategories = (props) => {
	const {
		label = __('Categories', 'patterns-store'),
		options,
		param = 'categories',
		setQueryArgs,
		value,
	} = props;

	if (!options.length) {
		return null;
	}

	const handleCategoryToggle = (categorySlug, newValue) => {
		// Split the comma-separated categories into an array
		let currentCategoriesArray = value ? value.split(',') : [];

		if (newValue) {
			// Add the new category value to the array if it doesn't exist
			if (!currentCategoriesArray.includes(categorySlug)) {
				currentCategoriesArray.push(categorySlug);
			}
		} else {
			// Remove the category value from the array if it exists
			const index = currentCategoriesArray.indexOf(categorySlug);
			if (index !== -1) {
				currentCategoriesArray.splice(index, 1);
			}
		}

		// Join the array back into a comma-separated string
		const newCategories = currentCategoriesArray.join(',');

		// Update the URL with the new categories
		if (newCategories) {
			setQueryArgs({
				[param]: newCategories,
			});
		} else {
			setQueryArgs({
				[param]: null,
			});
		}
	};

	return (
		<AtrcWrap
			tag='div'
			className='ps-card at-bg-cl at-bg-white at-p at-bdr at-m'>
			<AtrcText
				tag='h5'
				className='at-m'>
				{label}
			</AtrcText>
			<AtrcHr />
			<AtrcList className='ps-cat at-flx at-flx-col at-gap at-m'>
				{options.map((i) => (
					<AtrcLi key={i.value}>
						<AtrcControlToggle
							label={i.label}
							checked={value && value.includes(i.value)}
							onChange={(newValue) => handleCategoryToggle(i.value, newValue)}
						/>
					</AtrcLi>
				))}
			</AtrcList>
		</AtrcWrap>
	);
};

export default SelectCategories;
