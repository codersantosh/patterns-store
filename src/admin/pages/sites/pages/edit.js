/* WordPress */
import { __ } from '@wordpress/i18n';

import { useEffect } from '@wordpress/element';

/* Library */
import { isEmpty, find } from 'lodash';

/*Atrc*/
import { AtrcWrap, AtrcSpinner, AtrcUseParams } from 'atrc';

/*Inbuilt*/
import { Form } from '../organisms';

/*Local*/
const Edit = (props) => {
	const { items, item, getItem, setItem } = props;

	const { id } = AtrcUseParams(),
		itemId = parseInt(id);

	useEffect(() => {
		if (items && (!item || isEmpty(item) || item.id !== itemId)) {
			const singleItem = find(items, (itm) => itm.id === parseInt(id));
			if (!singleItem) {
				getItem(itemId);
			} else {
				setItem(singleItem);
			}
		}
	}, [items]);

	if (!item || !item.id) {
		return <AtrcSpinner />;
	}

	if (item.id !== itemId) {
		return <AtrcWrap>{__('Something went wrong!')}</AtrcWrap>;
	}

	return (
		<>
			{item.notFound ? (
				<AtrcWrap>{__('Nothing found')}</AtrcWrap>
			) : (
				<Form {...props} />
			)}
		</>
	);
};

export default Edit;
