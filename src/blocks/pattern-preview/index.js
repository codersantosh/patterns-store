/* WordPress */
import { registerBlockType } from '@wordpress/blocks';

/* Internal */
/* same as patterns copy */
import Edit from '../pattern-copy/edit';
import Save from '../pattern-copy/save';

import metadata from './block.json';

registerBlockType(metadata.name, {
    edit: Edit,
    save: Save,
});
