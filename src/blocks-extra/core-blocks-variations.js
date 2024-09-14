/* WordPress */
import { registerBlockVariation } from '@wordpress/blocks';
import { __ } from '@wordpress/i18n';

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
    name: 'patterns-store/all-pattern-filter',
    title: __('All pattern filter', 'patterns-store'),
    description: __(
        'Displays a button with the all pattern filter link.',
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
    name: 'patterns-store/pattern-kits-filter',
    title: __('Pattern kits filter', 'patterns-store'),
    description: __(
        'Displays a button with the pattern kits filter link.',
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
    name: 'patterns-store/patterns-filter',
    title: __('Patterns filter', 'patterns-store'),
    description: __('Displays a button with the patterns filter link', 'patterns-store'),
    category: 'widgets',
    keywords: ['pattern'],
    scope: ['inserter'],
    attributes: {
        text: __('Patterns', 'patterns-store'),
        metadata: {
            bindings: {
                url: {
                    source: 'patterns-store/pattern-type-link',
                    args: {
                        key: 'patterns',
                    },
                },
            },
        },
    },
    example: {},
    isActive: (blockAttributes) =>
        'patterns' === blockAttributes?.metadata?.bindings?.url?.args?.key,
});

/* Add variation for pattern parent link, pattern copy and pattern preview
the atribute patterns-store-pattern-button-type is defined on core-blocks-extra-options.js*/
registerBlockVariation('core/button', {
    name: 'patterns-store/parent-link',
    title: __('Pattern Kits (Parent) link', 'patterns-store'),
    description: __('This button will display a link to the parent pattern kit.', 'patterns-store'),
    attributes: {
        'patterns-store-pattern-button-type': 'parent-link',
    },
    isActive: (blockAttributes) => {
        return 'parent-link' === blockAttributes?.['patterns-store-pattern-button-type'];
    },
});
registerBlockVariation('core/button', {
    name: 'patterns-store/pattern-copy',
    title: __('Pattern Copy', 'patterns-store'),
    description: __('This button copies the current pattern code to the clipboard when clicked.', 'patterns-store'),
    attributes: {
        'tagName': 'button',
        'patterns-store-pattern-button-type': 'pattern-copy'
    },
    isActive: (blockAttributes) => {
        return 'pattern-copy' === blockAttributes?.['patterns-store-pattern-button-type'];
    },
});
registerBlockVariation('core/button', {
    name: 'patterns-store/pattern-preview',
    title: __('Pattern Preview', 'patterns-store'),
    description: __('This button displays a preview of the current pattern when clicked.', 'patterns-store'),
    attributes: {
        'tagName': 'button',
        'patterns-store-pattern-button-type': 'pattern-preview'
    },
    isActive: (blockAttributes) => {
        return 'pattern-preview' === blockAttributes?.['patterns-store-pattern-button-type'];
    },
});