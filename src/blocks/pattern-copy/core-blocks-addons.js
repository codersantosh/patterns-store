/* WordPress */
import { registerBlockVariation } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';
import { addFilter } from '@wordpress/hooks';

/* Add parent to core button */
function patternsStoreAddCoreButtonParents(settings, name) {
    if (name === 'core/button') {
        const clonedParent = settings.parent ? [...settings.parent] : [];
        clonedParent.push('patterns-store/pattern-copy');
        clonedParent.push('patterns-store/pattern-preview');
        return {
            ...settings,
            parent: clonedParent,
        };
    }
    return settings;
}

addFilter(
    'blocks.registerBlockType',
    'patterns-store/add-core-button-parents',
    patternsStoreAddCoreButtonParents
);

/* Show number of patterns in pattern kit or link to patterm kit in pattern  */
registerBlockVariation('core/paragraph', {
    name: 'patterns-store/pattern-relation',
    title: __('Pattern relation', 'patterns-store'),
    description: __('Displays the pattern relation.', 'patterns-store'),
    category: 'text',
    keywords: ['pattern', 'relation', 'parent', 'child'],
    scope: ['inserter'],
    attributes: {
        metadata: {
            bindings: {
                content: {
                    source: 'patterns-store/pattern-data',
                    args: {
                        key: 'patterns_store_pattern_relation',
                    },
                },
            },
        },
        placeholder: __('Relation', 'patterns-store'),
    },
    example: {},
    isActive: (blockAttributes) =>
        'patterns_store_pattern_relation' ===
        blockAttributes?.metadata?.bindings?.content?.args?.key,
});

/* Show related pattern title*/
registerBlockVariation('core/heading', {
    name: 'patterns-store/related-items-title',
    title: __('Pattern related title', 'patterns-store'),
    description: __('Displays the related query title.', 'patterns-store'),
    category: 'text',
    keywords: ['pattern', 'related', 'title'],
    scope: ['inserter'],
    attributes: {
        metadata: {
            bindings: {
                content: {
                    source: 'patterns-store/related-items-title',
                    args: {
                        key: 'patterns_store_pattern_related_title',
                    },
                },
            },
        },
        placeholder: __('Pattern related title', 'patterns-store'),
    },
    example: {},
    isActive: (blockAttributes) =>
        'patterns_store_pattern_related_title' ===
        blockAttributes?.metadata?.bindings?.content?.args?.key,
});

/* Add variation for pattern type link */
registerBlockVariation('core/button', {
    name: 'patterns-store/all-pattern-link',
    title: __('All pattern filter', 'patterns-store'),
    description: __(
        'Displays a button with the all pattern link.',
        'patterns-store'
    ),
    category: 'widgets',
    keywords: ['pattern', 'type', 'all'],
    scope: ['inserter'],
    attributes: {
        text: __('All', 'patterns-store'),
        metadata: {
            bindings: {
                url: {
                    source: 'patterns-store/pattern-type-link',
                    args: {
                        key: 'all',
                    },
                },
            },
        },
    },
    example: {},
    isActive: (blockAttributes) =>
        'all' === blockAttributes?.metadata?.bindings?.url?.args?.key,
});

registerBlockVariation('core/button', {
    name: 'patterns-store/pattern-kits-link',
    title: __('Pattern kits filter', 'patterns-store'),
    description: __(
        'Displays a button with the pattern kits link.',
        'patterns-store'
    ),
    category: 'widgets',
    keywords: ['pattern', 'pattern kits'],
    scope: ['inserter'],
    attributes: {
        text: __('Pattern kits', 'patterns-store'),
        metadata: {
            bindings: {
                url: {
                    source: 'patterns-store/pattern-type-link',
                    args: {
                        key: 'pattern-kits',
                    },
                },
            },
        },
    },
    example: {},
    isActive: (blockAttributes) =>
        'pattern-kits' === blockAttributes?.metadata?.bindings?.url?.args?.key,
});

registerBlockVariation('core/button', {
    name: 'patterns-store/parent-link',
    title: __('Pattern kits link', 'patterns-store'),
    description: __('Displays a button with the parent link.', 'patterns-store'),
    category: 'widgets',
    keywords: ['pattern', 'pattern kits'],
    scope: ['inserter'],
    attributes: {
        text: __('View pattern kits', 'patterns-store'),
        metadata: {
            bindings: {
                url: {
                    source: 'patterns-store/pattern-type-link',
                    args: {
                        key: 'parent-link',
                    },
                },
            },
        },
    },
    example: {},
    isActive: (blockAttributes) =>
        'parent-link' === blockAttributes?.metadata?.bindings?.url?.args?.key,
});
