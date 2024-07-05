/* WordPress */
import { useViewportMatch } from '@wordpress/compose';

/* Atrc */
import { AtrcControlSelect } from 'atrc';

/* Local */
const PatternSelectControl = ({
	defaultValue,
	label,
	param,
	options,
	value,
	setQueryArgs,
}) => {
	const hideLabel = useViewportMatch('medium', '>=');

	if (!options) {
		return null;
	}

	return (
		<AtrcControlSelect
			allowReset={false}
			label={label}
			hideLabelFromVision={hideLabel}
			value={value}
			options={options}
			onChange={(newValue) => {
				setQueryArgs({
					[param]: newValue,
				});
			}}
		/>
	);
};

export default PatternSelectControl;
