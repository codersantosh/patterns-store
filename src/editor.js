/* *********===================== Setup store ======================********* */
import { AtrcApis, AtrcStore, AtrcRegisterStore } from 'atrc/build/data';

AtrcApis.baseUrl({
	key: 'atrc-global-api-base-url',
	// eslint-disable-next-line no-undef
	url: PatternsStoreLocalize.rest_url,
});

/* Custom pattern-meta */
AtrcApis.register({
	key: 'patternMeta',
	path: 'patterns-store/v1/pattern-meta',
});

/* Custom theme json */
AtrcApis.register({
	key: 'patternCustomThemeJson',
	path: 'patterns-store/v1/theme-json',
});

/* Settings Local for user preferance */
AtrcStore.register({
	key: 'patternsStoreLocal',
	type: 'localStorage',
});

// eslint-disable-next-line no-undef
AtrcApis.xWpNonce(PatternsStoreLocalize.nonce);
window.atrcStore = AtrcRegisterStore(PatternsStoreLocalize.store);

import './editor/index.js';
