/* WordPress */
import { __ } from '@wordpress/i18n';
import { addFilter, hasFilter } from '@wordpress/hooks';
import { hasBlockSupport } from '@wordpress/blocks';
import { InspectorControls } from '@wordpress/block-editor';
import { PanelBody } from '@wordpress/components';

/*Atrc*/
import {
	AtrcControlCodeTextarea,
	AtrcFormatCss,
	AtrcGetProcessedCss,
} from 'atrc';

/* Local */
function addAttribute(settings) {
	// Allow blocks to specify their own attribute definition with default values if needed.
	if ('type' in (settings.attributes?.patternsStoreCustomCss ?? {})) {
		return settings;
	}
	if (hasBlockSupport(settings, 'customClassName')) {
		// Gracefully handle if settings.attributes is undefined.
		settings.attributes = {
			...settings.attributes,
			patternsStoreCustomCss: {
				type: 'string',
			},
		};
	}

	return settings;
}
addFilter(
	'blocks.registerBlockType',
	'patterns-store/custom-css/addAttribute',
	addAttribute
);

const PatternsStoreCustomCss = ({ props: { attributes, setAttributes } }) => {
	const { patternsStoreCustomCss = '' } = attributes;
	return (
		<PanelBody title={__('Custom CSS', 'patterns-store')}>
			<AtrcControlCodeTextarea
				label={__('Add custom CSS', 'patterns-store')}
				value={AtrcFormatCss(patternsStoreCustomCss)}
				onChange={(newCss) =>
					setAttributes({ patternsStoreCustomCss: AtrcGetProcessedCss(newCss) })
				}
			/>
		</PanelBody>
	);
};

const withPatternsStoreCustomCss = (BlockEdit) => (props) => {
	if (!props || !props.name) {
		return <BlockEdit {...props} />;
	}
	return (
		<>
			<BlockEdit {...props} />
			<InspectorControls>
				<PatternsStoreCustomCss props={props} />
			</InspectorControls>
		</>
	);
};

if (!hasFilter('editor.BlockEdit', 'patterns-store/custom-css/control')) {
	addFilter(
		'editor.BlockEdit',
		'patterns-store/custom-css/control',
		withPatternsStoreCustomCss,
		99
	);
}
