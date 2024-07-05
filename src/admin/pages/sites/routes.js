import { useCallback as AtrcUseCallback } from '@wordpress/element';

import { map, isEmpty } from 'lodash';

/*Atrc*/
import {
    AtrcSpinner,
    AtrcNotice,
    AtrcWrapFloating,
    AtrcUseDelayFunction,
    AtrcRoute,
    AtrcRoutes,
} from 'atrc';

import { AtrcApplyWithItems } from 'atrc/build/data';

/*Inbuilt*/
import { Create, Edit, Lists } from './pages';

/*Local*/
const SiteRouters = (props) => {
    const { countAllItems, notices, removeNotice } = props;

    /*Handling notice for all list, add and edit*/
    const useDelayedFn = AtrcUseCallback(AtrcUseDelayFunction, []);
    const { dlaFn } = useDelayedFn(removeNotice);

    if (countAllItems === null) {
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
                    path={'create'}
                    element={<Create {...props} />}
                />
                <AtrcRoute
                    exact
                    path='edit/:id'
                    element={<Edit {...props} />}
                />
            </AtrcRoutes>
            {/*Notice is common*/}
            {!isEmpty(notices) ? (
                <AtrcWrapFloating>
                    {map(notices, (value, key) => (
                        <AtrcNotice
                            key={key}
                            status={value.type}
                            autoDismiss={5000}
                            onAutoRemove={() => {
                                if ('error' !== value.type) {
                                    dlaFn(key);
                                }
                            }}
                            onRemove={() => removeNotice(key)}>
                            {value.message}
                        </AtrcNotice>
                    ))}
                </AtrcWrapFloating>
            ) : null}
        </>
    );
};

const SiteRoutersWithHoc = AtrcApplyWithItems(SiteRouters);

const InitSites = () => {
    return (
        <SiteRoutersWithHoc
            atrcStore='patterns-store'
            atrcStoreKey='sites'
            refreshOnUrlChange={true}
        />
    );
};
export default InitSites;
