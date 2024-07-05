/* WordPress */
import { useState } from '@wordpress/element';

/* Inbuilt */
import PatternSinglePreview from './patterns-single-preview';

/* Local */
const withPreviewPattern = (WrappedComponent) => {
	return function WithStatePattern(props) {
		const { patterns = [] } = props;

		const [currentPattern, setPattern] = useState();

		const handlePatternChange = (newPattern) => {
			setPattern(newPattern);
		};

		if (currentPattern) {
			return (
				<>
					<PatternSinglePreview
						currentPattern={currentPattern}
						onPreviewPatternChange={handlePatternChange}
						posts={patterns}
					/>
					<WrappedComponent
						currentPattern={currentPattern}
						onPreviewPatternChange={handlePatternChange}
						{...props}
					/>
				</>
			);
		}
		return (
			<WrappedComponent
				currentPattern={currentPattern}
				onPreviewPatternChange={handlePatternChange}
				{...props}
			/>
		);
	};
};

export default withPreviewPattern;
