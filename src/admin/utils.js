/* Local */
const AtrcAdminGetLastWordInUrlPath = () => {
	const pathWithoutHash = window.location.pathname.replace(/#.*/, '');
	const pathSegments = pathWithoutHash.split('/');
	const lastSegment = pathSegments[pathSegments.length - 1];
	return lastSegment || pathSegments[pathSegments.length - 2] || '';
};

export { AtrcAdminGetLastWordInUrlPath };
