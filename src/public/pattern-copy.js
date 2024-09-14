/* WordPres */
import { __ } from '@wordpress/i18n';
import { speak } from '@wordpress/a11y';
import { render, useState, useEffect } from '@wordpress/element';

/* Atrc */
import { AtrcCopyToClipboard } from 'atrc';

/* Internal */
import PatternsStoreDynamicElement from '../utils/dynamic-div';
import { BlocksPatternCopyOrMessage } from '../admin/pages/patterns/components/pattern-copy-or-message';
import { PatternsStoreGetPatterData } from './pattern-preview';
import { ModalLoading } from '../admin/components/molecules';

/* Local */
const InitBlocksPatternCopyOrMessage = ({ element, button }) => {
    const patternInfo = { id: button.getAttribute('data-id') };
    let messageType = '';
    let popupType = '';

    const [dynamicPattern, setDynamicPattern] = useState();

    useEffect(() => {
        const getDynamicPattern = async () => {
            const getPatternKit = await PatternsStoreGetPatterData({
                id: patternInfo.id,
            });
            if (getPatternKit && getPatternKit.patterns) {
                patternInfo.patterns = getPatternKit.patterns;
            }
            setDynamicPattern(patternInfo)
        }
        if (button.classList.contains('pattern-store-button-pattern-kit')) {
            getDynamicPattern();
        }
    }, [patternInfo.id, button.classList]);

    if (button.classList.contains('pattern-store-button-pattern')) {
        const input = element.querySelector(
            '.wp-block-patterns-store-copy-button__content'
        );
        const content = JSON.parse(decodeURIComponent(input.value));

        const success = AtrcCopyToClipboard(content);

        // Make sure we reset focus in case it was lost in the 'copy' command.
        button.focus();

        if (success) {
            speak(__('Copied pattern to clipboard.', 'patterns-store'));
            button.innerText = button.dataset.labelSuccess;
            messageType = 'copy';
            setTimeout(() => {
                button.innerText = button.dataset.label;
            }, 5000);
        }
    } else if (
        button.classList.contains('pattern-store-button-pattern-kit')
    ) {
        popupType = 'patterns';

    } else if (
        button.classList.contains('pattern-store-button-no-access')
    ) {
        messageType = 'access-denied';
    }

    if (!messageType && !popupType) {
        return null;
    }

    if ('patterns' === popupType && !dynamicPattern) {
        return (
            <ModalLoading />
        )
    }
    return <BlocksPatternCopyOrMessage
        messageType={messageType}
        popupType={popupType}
        pattern={dynamicPattern}
    />
}

const PatternsStorePatternCopyInit = () => {
    const containers = document.querySelectorAll(
        '.wp-block-patterns-store-pattern-copy'
    );
    if (containers) {
        containers.forEach((element) => {
            const button = element.querySelector('button');

            button.disabled = false;
            button.onclick = async () => {


                // Add element to dom/body
                const patternStoreDynamicDiv = PatternsStoreDynamicElement();
                document.body.appendChild(patternStoreDynamicDiv);

                // Render the component into the dynamic element
                render(
                    <InitBlocksPatternCopyOrMessage
                        button={button}
                        element={element}
                    />,
                    patternStoreDynamicDiv
                );
            };
        });
    }
};

window.addEventListener('load', PatternsStorePatternCopyInit);
