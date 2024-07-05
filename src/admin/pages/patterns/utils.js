export const AtrcRemoveDoubleSlashesFromUrl = (url) => {
	// Replace double slashes after the protocol part
	return url.replace(/([^:]\/)\/+/g, '$1');
};

export const PatternsStoreFilterPricingIsShow = ({
	item,
	filterProductType,
	filterLicense,
	filterRecurring,
}) => {
	const { pricingData } = PatternsStoreLocalize;

	if (!item) {
		return false;
	}

	/* if product type wise pricing show all columns because each column show product type pricing */
	if ('variation' !== pricingData.planView.column) {
		return true;
	}

	if (
		pricingData.planView.view &&
		'product-type' in item &&
		item['product-type'] !== filterProductType
	) {
		return false;
	}

	if (
		pricingData.licView &&
		'license' in item &&
		parseInt(item['license']) !== parseInt(filterLicense)
	) {
		return false;
	}

	if (
		pricingData.recView &&
		'recurring' in item &&
		item['recurring'] !== filterRecurring
	) {
		return false;
	}

	return true;
};

const getColSuffixNum = (colNum) => {
	const colNumCases = new Map([
		[5, 3],
		[1, 12],
		[2, 6],
		[3, 4],
		[4, 3],
		[6, 3],
	]);

	return colNumCases.get(colNum) || '';
};

const PatternsStoreGetColClasses = (numOfCol) => {
	let inhCls = '';

	if (!numOfCol) {
		return inhCls;
	}

	const colSuffixNum = getColSuffixNum(numOfCol);

	inhCls += colSuffixNum ? `at-col-${colSuffixNum}` : 'at-col';

	return inhCls;
};

export default PatternsStoreGetColClasses;
