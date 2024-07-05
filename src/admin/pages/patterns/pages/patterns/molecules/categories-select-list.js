/* WordPress */
import { __ } from '@wordpress/i18n';

/* Inbuilt */
import SelectCategories from './categories-select';

/* Local */
const CategoriesSelectList = ({
	categories,
	options = [],
	setQueryArgs,
	value,
	param,
}) => {
	return (
		<SelectCategories
			label={__('Select categories', 'patterns-store')}
			options={options}
			value={value}
			param={param}
			setQueryArgs={setQueryArgs}
		/>
	);
};

export default CategoriesSelectList;
