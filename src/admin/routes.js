/*CSS*/
import './admin.scss';

/* WordPress */
import {
    render,
    createContext,
    useContext,
    useCallback,
} from '@wordpress/element';

/* Library */
import { map, isEmpty } from 'lodash';

/*Atrc*/
import {
    AtrcHashRouter,
    AtrcRoute,
    AtrcRoutes,
    AtrcWrap,
    AtrcNotice,
    AtrcWrapFloating,
    AtrcUseDelayFunction,
    AtrcMain,
} from 'atrc';

import { AtrcApplyWithSettings } from 'atrc/build/data';

/*Inbuilt*/
import AdminHeader from './components/organisms/admin-header';
import Initlanding from './pages/landing';
import InitSettings from './pages/settings/routes';
import InitPatterns from './pages/patterns/routes';
import InitPricing from './pages/pricing/routes';
import InitSites from './pages/sites/routes';

/* Local */

/* ==============Create Local Storage and Database Settings Context================== */
export const AtrcReduxContextData = createContext();
const AdminRoutes = () => {
    const data = useContext(AtrcReduxContextData);
    const { dbNotices, dbRemoveNotice } = data;

    /*Handling notice for all list, add and edit*/
    const useDelayedFn = useCallback(AtrcUseDelayFunction, []);
    const { dlaFn } = useDelayedFn(dbRemoveNotice);

    return (
        <>
            <AdminHeader />
            <AtrcMain>
                <AtrcRoutes>
                    <AtrcRoute
                        index
                        element={<Initlanding />}
                    />
                    <AtrcRoute
                        exact
                        path='/settings/*'
                        element={<InitSettings />}
                    />
                    <AtrcRoute
                        exact
                        path='/patterns/*'
                        element={<InitPatterns />}
                    />
                    <AtrcRoute
                        exact
                        path='/pricing/*'
                        element={<InitPricing />}
                    />
                    <AtrcRoute
                        exact
                        path='/sites/*'
                        element={<InitSites />}
                    />
                </AtrcRoutes>
                {/*Notice is common for settings*/}
                {!isEmpty(dbNotices) ? (
                    <AtrcWrapFloating>
                        {map(dbNotices, (value, key) => (
                            <AtrcNotice
                                key={key}
                                autoDismiss={5000}
                                onAutoRemove={() => dbRemoveNotice(key)}
                                onRemove={() => dbRemoveNotice(key)}>
                                {value.message}
                            </AtrcNotice>
                        ))}
                    </AtrcWrapFloating>
                ) : null}
            </AtrcMain>
        </>
    );
};

const InitDatabaseSettings = (props) => {
    const {
        isLoading,
        canSave,
        settings,
        updateSetting,
        saveSettings,
        notices,
        removeNotice,
        lsSettings,
        lsUpdateSetting,
        lsSaveSettings,
    } = props;

    const dbProps = {
        dbIsLoading: isLoading,
        dbCanSave: canSave,
        dbSettings: settings,
        dbUpdateSetting: updateSetting,
        dbSaveSettings: saveSettings,
        dbNotices: notices,
        dbRemoveNotice: removeNotice,
        lsSettings: lsSettings,
        lsUpdateSetting: lsUpdateSetting,
        lsSaveSettings: lsSaveSettings,
    };
    return (
        <AtrcReduxContextData.Provider value={{ ...dbProps }}>
            <AtrcHashRouter basename='/'>
                <AtrcWrap
                    variant='wrp'
                    className='at-box-szg at-m at-typ'>
                    <AdminRoutes />
                </AtrcWrap>
            </AtrcHashRouter>
        </AtrcReduxContextData.Provider>
    );
};
const InitDataBaseSettingsWithHoc = AtrcApplyWithSettings(InitDatabaseSettings);
const InitLocalStorageSettings = (props) => {
    const { settings, updateSetting, saveSettings } = props;
    const defaultSettings = {
        gs1: true /* getting started 1 */,
    };
    return (
        <InitDataBaseSettingsWithHoc
            atrcStore='patterns-store'
            atrcStoreKey='settings'
            lsSettings={settings || defaultSettings}
            lsUpdateSetting={updateSetting}
            lsSaveSettings={saveSettings}
        />
    );
};
const InitLocalStorageSettingsWithHoc = AtrcApplyWithSettings(
    InitLocalStorageSettings
);

document.addEventListener('DOMContentLoaded', () => {
    // Check if the root element exists in the DOM
    const rootElement = document.getElementById(PatternsStoreLocalize.root_id);

    if (rootElement) {
        // Render the component into the root element
        render(
            <InitLocalStorageSettingsWithHoc
                atrcStore='patterns-store'
                atrcStoreKey='patternsStoreLocal'
            />,
            rootElement
        );
    }
});
