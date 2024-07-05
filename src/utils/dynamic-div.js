export default function PatternsStoreDynamicElement() {
	const existingEl = document.getElementById(
		'pattern-store-dynamic-div-react-js'
	);

	if (existingEl) {
		// If the div exists, remove it
		existingEl.parentNode.removeChild(existingEl);
	}

	// Create a new div element
	const patternStoredynamicDiv = document.createElement('div');
	patternStoredynamicDiv.setAttribute(
		'id',
		'pattern-store-dynamic-div-react-js'
	);

	return patternStoredynamicDiv;
}
