/* WordPress */
import { __ } from '@wordpress/i18n';
import { speak } from '@wordpress/a11y';
import { useEffect, useState } from '@wordpress/element';

/* Library */
import { noop } from 'lodash';

/* Atrc */
import { AtrcButton, AtrcCopyToClipboard } from 'atrc';

/* Local */
const CopyOrBuy = (props) => {
    const {
        setMessageType = noop,
        access,
        content = '',
        productType,
        link,
        showGuide = true,
    } = props;

    const [copied, setCopied] = useState(false);

    const handleClick = async ({ target, type }) => {
        if ('copy' === type) {
            const success = await AtrcCopyToClipboard(content);

            setCopied(success);
        }

        // Make sure we reset focus in case it was lost.
        target.focus();

        /* When showing basic info no need to show message and guide */
        if (showGuide) {
            setMessageType(type);
        }
    };

    useEffect(() => {
        if (!copied) {
            return;
        }

        speak(__('Copied pattern to clipboard.', 'patterns-store'));

        const timer = setTimeout(() => setCopied(false), 5000);
        return () => {
            clearTimeout(timer);
        };
    }, [copied]);

    if (!access || !access.has_access) {
        return (
            <AtrcButton
                onClick={(e) =>
                    handleClick({ target: e.target, type: 'access-denied' })
                }>
                {__('Copy Denied!', 'patterns-store')}
            </AtrcButton>
        );
    }

    if ('pattern-kit' === productType) {
        if (!showGuide) {
            return null;
        }
        return (
            <AtrcButton
                onClick={(e) => handleClick({ target: e.target, type: 'patterns' })}>
                {__('Details', 'patterns-store')}
            </AtrcButton>
        );
    }

    let label = copied
        ? __('Copied', 'patterns-store')
        : __('Copy', 'patterns-store');

    return (
        <AtrcButton
            onClick={(e) => handleClick({ target: e.target, type: 'copy' })}>
            {label}
        </AtrcButton>
    );
};

export default CopyOrBuy;
