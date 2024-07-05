import './public/index';

/* Fixing edd buy now on model */
jQuery(document).ajaxComplete(function (event, xhr, options) {
	if (jQuery('.ps-pricing__modal').length > 0) {
		// Parsing the query string
		const params = new URLSearchParams(options.data);

		// Check if the action is "edd_add_to_cart"
		if (params.has('action') && params.get('action') === 'edd_add_to_cart') {
			// Find all buttons with data-edd-loading attribute inside ps-pricing__modal and hide them
			jQuery('.ps-pricing__modal [data-edd-loading]').hide();
		}
	}
});
