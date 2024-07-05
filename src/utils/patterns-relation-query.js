/* WordPress */
import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';

/* Atrc */
import { AtrcControlToggle } from 'atrc';

/* Local */
const PatternsRelationControls = ({ props: { attributes, setAttributes } }) => {
    const { query } = attributes;
    return (
        <PanelBody title={__('Patterns relations', 'patterns-store')}>
            <AtrcControlToggle
                label={__('Enable pattern relationships in query', 'patterns-store')}
                help={__(
                    'Use this within a single pattern query to display pattern relationships. For pattern kits, it will show the patterns within the kit, and for individual patterns, it will show related patterns. It will only work on a single pattern page.',
                    'patterns-store'
                )}
                checked={query?.patternsStoreRelation || false}
                onChange={() => {
                    setAttributes({
                        query: {
                            ...query,
                            patternsStoreRelation: !query.patternsStoreRelation,
                        },
                    });
                }}
            />
        </PanelBody>
    );
};

const withPatternsRelationControls = (BlockEdit) => (props) => {
    return 'core/query' === props.name ? (
        <>
            <BlockEdit {...props} />
            <InspectorControls>
                <PatternsRelationControls props={props} />
            </InspectorControls>
        </>
    ) : (
        <BlockEdit {...props} />
    );
};

addFilter('editor.BlockEdit', 'core/query', withPatternsRelationControls);
