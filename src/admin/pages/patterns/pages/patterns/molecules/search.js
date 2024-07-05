/* Atrc */
import { AtrcSearch } from 'atrc';

/* Local */
const PatternSearch = ({ label, param, setQueryArgs, value }) => {
	return (
		<AtrcSearch
			label={label}
			value={value}
			allowButton={false}
			doSearch={(newValue) => {
				setQueryArgs({
					[param]: newValue,
				});
			}}
		/>
	);
};

export default PatternSearch;
