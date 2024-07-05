/* *********===================== Setup store ======================********* */
import { AtrcApis, AtrcStore, AtrcRegisterStore } from 'atrc/build/data';

AtrcApis.baseUrl({
    key: 'atrc-global-api-base-url',
    // eslint-disable-next-line no-undef
    url: PatternsStoreLocalize.rest_url,
});

/* Settings */
AtrcApis.register({
    key: 'settings',
    path: 'patterns-store/v1/settings',
    type: 'settings',
});
/* Settings Local for user preferance */
AtrcStore.register({
    key: 'patternsStoreLocal',
    type: 'localStorage',
});

/* Custom sites */
AtrcApis.register({
    key: 'sites',
    path: 'patterns-store/v1/sites',
    // filterData: ({ data, api }) => {
    // 	if ('updateItem' === api.type || 'insertItem' === api.type) {
    // 		const siteData = {
    // 			version: '',
    // 			title: '',
    // 			url: '',
    // 			author: 0,
    // 			created_at: null,
    // 		};
    // 		if (data.version) {
    // 			siteData.version = data.version;
    // 		}
    // 		if (data.author) {
    // 			siteData.author = data.author;
    // 		}
    // 		if (data.created_at) {
    // 			siteData.created_at = data.created_at;
    // 		}
    // 		if (data.url) {
    // 			siteData.url = data.url;
    // 		}
    // 		if (data.title) {
    // 			siteData.title = data.title;
    // 		}
    // 		return siteData;
    // 	}
    // 	return data;
    // },
});

// eslint-disable-next-line no-undef
AtrcApis.xWpNonce(PatternsStoreLocalize.nonce);
window.atrcStore = AtrcRegisterStore(PatternsStoreLocalize.store);

import './admin/routes';

