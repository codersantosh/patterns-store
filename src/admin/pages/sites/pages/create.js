/* WordPress */
import { useEffect } from '@wordpress/element';

/*Atrc*/
import { AtrcUseNavigate, AtrcUseLocation } from 'atrc';

/*Inbuilt*/
import { Form } from '../organisms';

/*Local*/
const Create = (props) => {
	const { item } = props;
	/*Redirect to edit page if in create page and have id*/
	const location = AtrcUseLocation();
	const navigate = AtrcUseNavigate();
	useEffect(() => {
		if (item && item.id && location.pathname.includes('create')) {
			navigate(location.pathname.replace('create', `edit/${item.id}`));
		}
	}, [item, location.pathname]);

	return <Form {...props} />;
};

export default Create;
