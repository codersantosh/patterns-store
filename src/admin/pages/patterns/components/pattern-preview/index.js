/* WordPress */
import { useState } from '@wordpress/element';

/* Internal */
import PatternSinglePreview from './patterns-single-preview';

/* Local */
const PatternPreview = ({ posts, pattern }) => {
	const [currentPattern, setPattern] = useState(pattern);

	const handlePatternChange = (newPattern) => {
		setPattern(newPattern);
	};

	if (!currentPattern) {
		return null;
	}
	return (
		<PatternSinglePreview
			currentPattern={currentPattern}
			onPreviewPatternChange={handlePatternChange}
			posts={posts}
		/>
	);
};

export default PatternPreview;
