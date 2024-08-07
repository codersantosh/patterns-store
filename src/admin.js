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
/* Settings Local storage for user preferance */
AtrcStore.register({
    key: 'patternsStoreLocal',
    type: 'localStorage',
});

/* Custom sites */
AtrcApis.register({
    key: 'sites',
    path: 'patterns-store/v1/sites',
});

// eslint-disable-next-line no-undef
AtrcApis.xWpNonce(PatternsStoreLocalize.nonce);
window.atrcStore = AtrcRegisterStore(PatternsStoreLocalize.store);

import './admin/routes';

