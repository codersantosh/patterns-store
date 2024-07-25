/* WordPress */
import { useBlockProps, useInnerBlocksProps } from '@wordpress/block-editor';

/*Atrc*/
import { AtrcWrap } from 'atrc';

/*Local*/
function Edit({ attributes, clientId, tagName = 'button' }) {
    const blockProps = useBlockProps({
        className: 'wp-block-buttons',
    });

    /* Inner blocks template and props */
    const TEMPLATE = [
        [
            'core/button',
            {
                tagName: tagName,
            },
        ],
    ];

    const innerProps = {
        allowedBlocks: ['core/button'],
        template: TEMPLATE,
        templateLock: true,
        renderAppender: false,
    };

    const InnerBlocksProps = useInnerBlocksProps(blockProps, innerProps);

    return (
        <AtrcWrap {...blockProps}>
            <AtrcWrap {...InnerBlocksProps} />
        </AtrcWrap>
    );
}

export default Edit;
