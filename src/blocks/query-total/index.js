/* WordPress */
import { registerBlockType } from '@wordpress/blocks';
import { useBlockProps } from '@wordpress/block-editor';
import { __ } from '@wordpress/i18n';

/* Atrc */
import { AtrcWrap } from 'atrc';

/* Internal */
import metadata from './block.json';

/* Local */
function Edit() {
    return (
        <AtrcWrap {...useBlockProps()}>{__('10 items', 'patterns-store')}</AtrcWrap>
    );
}

registerBlockType(metadata.name, {
    edit: Edit,
    save: () => null,
});
