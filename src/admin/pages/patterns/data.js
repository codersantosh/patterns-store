/*CSS*/
import './index.scss';

/* WordPress */

/* Atrc */
import { AtrcApis, AtrcRegisterStore } from 'atrc/build/data';

/* Internal */
import { AtrcRemoveDoubleSlashesFromUrl } from './utils';

/* Local */
const patternCatsRestUrl = AtrcRemoveDoubleSlashesFromUrl(
	PatternsStoreLocalize.rest_url + PatternsStoreLocalize.category_rest_url
);

const patternRestUrl = AtrcRemoveDoubleSlashesFromUrl(
	PatternsStoreLocalize.rest_url + PatternsStoreLocalize.post_type_rest_url
);

/* Store Setup */
/* Patterns categories*/
AtrcApis.register({
	key: 'categories',
	path: patternCatsRestUrl,
	allowedParams: [],
});

/* Patterns */
AtrcApis.register({
	key: 'patterns',
	path: patternRestUrl,
	allowedParams: ['categories', 'search', 'product-type', 'type', 'page'],
	queryParams: {
		page: {
			type: 'arg' /* url or arg */,
		},
	},
	queryArgs: {
		page: 1,
		'pattern-store': true,
	},
	filterQueryArgs: ({ data, api, hiddenQueryArgsData }) => {
		/* Mapping slugs too real query */
		if (
			data &&
			data.categories &&
			hiddenQueryArgsData &&
			hiddenQueryArgsData.categories
		) {
			let catArray = data.categories.split(',');

			data['edd-categories'] = [];
			catArray.forEach((cat) => {
				const index = hiddenQueryArgsData.categories.findIndex(
					(obj) => obj.slug === cat
				);
				if (index !== -1) {
					const foundCat = hiddenQueryArgsData.categories[index];
					data['edd-categories'].push(foundCat.id);
				}
			});
		}

		return data;
	},
});

AtrcApis.xWpNonce(PatternsStoreLocalize.nonce);

AtrcRegisterStore('patterns-store-patterns');
