/* Atrc */
import { AtrcSpinner } from 'atrc';

/* Inbuilt */
import PatternsLists from './lists';

/* Local */
const PatternsListsWrap = (props) => {
	const {
		items,
		totalPages,
		countQueryItems,
		queryArgs,
		setQueryArgs,
		categories = [],
		isArchive = false,
	} = props;

	if (null === totalPages) {
		return <AtrcSpinner />;
	}

	const setQueryArgsWithHiddenData = (args) => {
		setQueryArgs(args);
	};

	return (
		<PatternsLists
			patterns={items}
			categories={categories}
			totalPages={totalPages}
			countQueryItems={countQueryItems}
			queryArgs={queryArgs}
			isArchive={isArchive}
			setQueryArgs={setQueryArgsWithHiddenData}
		/>
	);
};

export default PatternsListsWrap;
