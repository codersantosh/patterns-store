import './data.js';

/* WordPress */
import { useCallback } from '@wordpress/element';

/*Atrc*/
import { AtrcRoute, AtrcRoutes, AtrcSpinner, AtrcUseDelayFunction } from 'atrc';

import { AtrcApplyWithItems } from 'atrc/build/data';

/*Inbuilt*/
import { Lists, Single } from './pages';

/*Local*/
const PatternRouters = (props) => {
	const { countQueryItems, notices, removeNotice } = props;

	/*Handling notice for all list, add and edit*/
	const useDelayedFn = useCallback(AtrcUseDelayFunction, []);
	const { dlaFn } = useDelayedFn(removeNotice);

	if (countQueryItems === undefined) {
		return <AtrcSpinner />;
	}

	return (
		<>
			<AtrcRoutes>
				<AtrcRoute
					index
					element={<Lists {...props} />}
				/>
				<AtrcRoute
					exact
					path='pattern/:id'
					element={<Single {...props} />}
				/>
			</AtrcRoutes>
		</>
	);
};
const PatternsListsWithHoc = AtrcApplyWithItems(PatternRouters);

const InitPatternsLists = ({ categories, isArchive = true }) => {
	return (
		<PatternsListsWithHoc
			atrcStore='patterns-store-patterns'
			atrcStoreKey='patterns'
			hiddenQueryArgsData={{
				categories: categories,
			}}
			categories={categories}
			isArchive={isArchive}
		/>
	);
};

const PatternsWithCategories = (props) => {
	const { items, isLoading } = props;
	if (isLoading) {
		return <AtrcSpinner />;
	}

	return <InitPatternsLists categories={items} />;
};

const PatternsWithCategoriesWithHoc = AtrcApplyWithItems(
	PatternsWithCategories
);

const InitPatternsWithCategoriesWithHoc = () => {
	return (
		<PatternsWithCategoriesWithHoc
			atrcStore='patterns-store-patterns'
			atrcStoreKey='categories'
		/>
	);
};
export default InitPatternsWithCategoriesWithHoc;
