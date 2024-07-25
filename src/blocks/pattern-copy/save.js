/* WordPress */
import { InnerBlocks } from '@wordpress/block-editor';

/*Atrc*/
import { AtrcWrap } from 'atrc';

/*Local*/
function Save({ attributes, clientId }) {
    return (
        <AtrcWrap className='wp-block-buttons'>
            <InnerBlocks.Content />
        </AtrcWrap>
    );
}

export default Save;
