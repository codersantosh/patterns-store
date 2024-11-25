/* WordPress */
import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';

/* Atrc */
import { AtrcControlToggle, AtrcControlText, AtrcPanelBody } from 'atrc';

/* Local */

/* Adding the Custom Attribute */
const addPatternsStoreAttributes = (settings, name) => {
    if ('core/post-terms' === name) {
        settings.attributes = Object.assign(settings.attributes, {
            'patterns-store-empty-text': {
                type: 'string',
            },
        });
    }
    if ('core/button' === name) {
        settings.attributes = Object.assign(settings.attributes, {
            'patterns-store-pattern-button-type': {
                type: 'string',
            },
        });
    }
    return settings;
};
addFilter('blocks.registerBlockType', 'patterns-store/core-blocks-extra-attributes', addPatternsStoreAttributes);

/* Adding the Custom Attribute */
const PatternsRelationControls = ({ props: { attributes, setAttributes } }) => {
    const { query } = attributes;
    return (
        <>
            <PanelBody title={__('Patterns relations', 'patterns-store')}>
                <AtrcControlToggle
                    label={__('Enable pattern relationships in query', 'patterns-store')}
                    help={__(
                        'Use this option in a single pattern template to display pattern relationships. For pattern kits, it will show the patterns within the kit, and for individual patterns, it will show related patterns. It will only work on a single pattern page.',
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
            {PatternsStoreEditorLocalize.has_pro ? <PanelBody title={__('Purchased items', 'patterns-store')}>
                <AtrcControlToggle
                    label={__('Show purchased items in query', 'patterns-store')}
                    help={__(
                        'Use this option to display items purchased by the logged-in user.',
                        'patterns-store'
                    )}
                    checked={query?.patternsStorePurchasedItems || false}
                    onChange={() => {
                        setAttributes({
                            query: {
                                ...query,
                                patternsStorePurchasedItems: !query.patternsStorePurchasedItems,
                            },
                        });
                    }}
                />
            </PanelBody> : null}

        </>
    );
};

/* Adding the Custom Controls */
const withPatternsRelationControls = (BlockEdit) => (props) => {
    const { attributes, setAttributes } = props;
    if ('core/query' === props.name) {
        return (
            <>
                <BlockEdit {...props} />
                <InspectorControls>
                    <PatternsRelationControls props={props} />
                </InspectorControls>
            </>
        )
    }
    if ('core/post-terms' === props.name) {
        return (
            <>
                <BlockEdit {...props} />
                <InspectorControls>
                    <AtrcPanelBody>
                        <AtrcControlText
                            label={__('Empty terms text', 'patterns-store')}
                            value={attributes['patterns-store-empty-text']}
                            onChange={(value) => setAttributes({ 'patterns-store-empty-text': value })}
                        />
                    </AtrcPanelBody>
                </InspectorControls>
            </>
        )
    }
    return <BlockEdit {...props} />
};

addFilter('editor.BlockEdit', 'patterns-store/core-blocks-extra-options', withPatternsRelationControls);
