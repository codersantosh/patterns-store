/* WordPress */
import { registerBlockType } from '@wordpress/blocks';

/* Core blocks addons/modifications */
import './core-blocks-addons';

/* Patterns relations query */
import '../../utils/patterns-relation-query';
import '../../utils/block-css.js';

/* Internal */
import metadata from './block.json';
import Edit from './edit';
import Save from './save';

registerBlockType(metadata.name, {
	edit: Edit,
	save: Save,
});
