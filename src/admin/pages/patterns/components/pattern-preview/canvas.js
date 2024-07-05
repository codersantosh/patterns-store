/* WordPress */
import { useState, useEffect } from '@wordpress/element';

import { __ } from '@wordpress/i18n';

/* Library */
import classnames from 'classnames';

/* Atrc */
import { AtrcIframe, AtrcSpinner } from 'atrc';

/* Local */
function Canvas({ url }) {
	const [isLoading, setIsLoading] = useState(true);

	const style = {
		width: '100%',
		height: '100%',
	};

	useEffect(() => {
		setIsLoading(true);
	}, [url]);

	const handleIframeLoad = () => {
		setIsLoading(false);
	};

	return (
		<>
			{isLoading ? <AtrcSpinner /> : null}
			<AtrcIframe
				className={classnames('ps-iframe', isLoading ? 'at-d-non' : '')}
				title={__('Pattern Preview', 'patterns-store')}
				tabIndex='-1'
				style={style}
				src={url}
				onLoad={handleIframeLoad}
			/>
		</>
	);
}

export default Canvas;
