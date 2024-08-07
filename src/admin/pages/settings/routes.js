/* WordPress */
import { __ } from '@wordpress/i18n';
import { useContext, useMemo } from '@wordpress/element';

/*Library*/

/*Atrc*/
import {
    AtrcRoute,
    AtrcRoutes,
    AtrcNavigate,
    AtrcNav,
    AtrcWireFrameSidebarContent,
    AtrcFooterTemplate1,
    AtrcButtonSaveTemplate1,
} from 'atrc';

/*Inbuilt*/
import { General, EDD, Advanced } from './pages';
import { AtrcReduxContextData } from '../../routes';

/*Local*/
const IconRouters = () => {
    const data = useContext(AtrcReduxContextData);
    const { dbIsLoading, dbCanSave, dbSettings, dbSaveSettings } = data;
    return (
        <>
            <AtrcRoutes>
                <AtrcRoute
                    index
                    exact
                    path='general/*'
                    element={<General />}
                />
                <AtrcRoute
                    exact
                    path='edd/*'
                    element={<EDD />}
                />
                <AtrcRoute
                    exact
                    path='advanced/*'
                    element={<Advanced />}
                />
                <AtrcRoute
                    path='/'
                    element={
                        <AtrcNavigate
                            to='general'
                            replace
                        />
                    }
                />
            </AtrcRoutes>
            <AtrcFooterTemplate1 useDynamicPosition={true}>
                <AtrcButtonSaveTemplate1
                    isLoading={dbIsLoading}
                    canSave={dbCanSave}
                    text={{
                        saved: __('Saved', 'patterns-store'),
                        save: __('Save settings', 'patterns-store'),
                    }}
                    disabled={dbIsLoading || !dbCanSave}
                    onClick={() => dbSaveSettings(dbSettings)}
                />
            </AtrcFooterTemplate1>
        </>
    );
};

const Settings = () => {
    const data = useContext(AtrcReduxContextData);
    const { dbSettings } = data;
    const { products = {} } = dbSettings;

    const { postType = '' } = products;

    const navs = useMemo(() => {
        if ('download' === postType) {
            return [
                {
                    to: 'general',
                    children: __('General', 'patterns-store'),
                },
                {
                    to: 'edd',
                    children: __('EDD', 'patterns-store'),
                },
                {
                    to: 'advanced',
                    children: __('Advanced', 'patterns-store'),
                },
            ];
        }
        return [
            {
                to: 'general',
                children: __('General', 'patterns-store'),
            },
            {
                to: 'advanced',
                children: __('Advanced', 'patterns-store'),
            },
        ];
    }, [postType]);

    return (
        <AtrcWireFrameSidebarContent
            wrapProps={{
                allowContainer: true,
                type: 'fluid',
                className: 'at-p',
            }}
            rowProps={{}}
            renderSidebar={
                <AtrcNav
                    variant='vertical'
                    navs={navs}
                />
            }
            renderContent={<IconRouters />}
            contentProps={{
                tag: 'div',
                contentCol: 'at-col-10',
            }}
            sidebarProps={{
                sidebarCol: 'at-col-2',
            }}
        />
    );
};

export default Settings;
