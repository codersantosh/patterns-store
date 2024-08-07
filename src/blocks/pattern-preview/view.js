/* WordPres */
import { __ } from '@wordpress/i18n';
import { render, useState, useEffect } from '@wordpress/element';

/* Library */

/* Atrc */

/* Internal */
import PatternsStoreDynamicElement from '../../utils/dynamic-div';
import PatternPreview from './../../admin/pages/patterns/components/pattern-preview';
import { AtrcRemoveDoubleSlashesFromUrl } from '../../admin/pages/patterns/utils';
import { ModalLoading } from '../../admin/components/molecules';

/* Local */
const patternRestUrl = AtrcRemoveDoubleSlashesFromUrl(
    PatternsStoreLocalize.rest_url + PatternsStoreLocalize.post_type_rest_url
);
const PatternsStoreGlobalRelatedItems = {};
async function PatternsStoreGetCurrentPageItems({ currentPattern, postIds }) {
    try {
        const cacheKey =
            postIds && postIds.length ? 'ps-' + postIds.join('-') : null;

        if (!cacheKey) {
            return null;
        }

        if (!PatternsStoreGlobalRelatedItems.hasOwnProperty(cacheKey)) {
            const urlObject = new URL(patternRestUrl);
            urlObject.searchParams.append('include', postIds.join(','));
            urlObject.searchParams.append('orderby', 'include');
            const response = await fetch(urlObject.toString());
            if (!response.ok) {
                throw new Error(__('Error fetching posts:', 'patterns-store') + `${response.statusText}`);
            }
            const data = await response.json();

            /* Caching */
            PatternsStoreGlobalRelatedItems[cacheKey] = data;
        }
        return PatternsStoreGlobalRelatedItems[cacheKey];
    } catch (error) {
        console.error(__('Error fetching posts:', 'patterns-store'), error);
    }
    return null;
}

const PatternsStoreGlobalPatterns = {};
export async function PatternsStoreGetPatterData({ id }) {
    try {
        if (!PatternsStoreGlobalPatterns.hasOwnProperty(id)) {
            /* check if  PatternsStoreGlobalRelatedItems has id */
            let cacheFound = null;
            if (Object.keys(PatternsStoreGlobalRelatedItems).length > 0) {
                for (let key in PatternsStoreGlobalRelatedItems) {
                    const cacheItems = PatternsStoreGlobalRelatedItems[key];
                    cacheFound = cacheItems.find(
                        (cacheItem) => parseInt(cacheItem.id) === parseInt(id)
                    );
                    if (cacheFound) {
                        break;
                    }
                }
            }
            if (cacheFound) {
                PatternsStoreGlobalPatterns[id] = cacheFound;
            } else {
                const urlObject = new URL(`${patternRestUrl}/${id}`);

                const response = await fetch(urlObject.toString());
                if (!response.ok) {
                    throw new Error(`${response.statusText}`);
                }
                const data = await response.json();

                /* Caching */
                PatternsStoreGlobalPatterns[id] = data;
            }
        }

        return PatternsStoreGlobalPatterns[id];
    } catch (error) {
        console.error(__('Error fetching posts:', 'patterns-store'), error);
    }

    return null;
}

function PatternsStoreGetPostIdsFromPreviewButtons(element) {
    const postIds = [];
    const postTemplates = element.closest('.wp-block-post-template');
    if (postTemplates) {
        const previewButtons = postTemplates.querySelectorAll(
            '.pattern-store-button-preview'
        );
        if (previewButtons) {
            // Convert NodeList to an array and sort by DOM position
            const orderedPreviewButtons = Array.from(previewButtons).sort((a, b) =>
                a.compareDocumentPosition(b)
            );

            orderedPreviewButtons.forEach((postData) => {
                postIds.push(parseInt(postData.getAttribute('data-id')));
            });
        }
    } else if (PatternsStoreLocalize.currentPattternId) {
        postIds.push(PatternsStoreLocalize.currentPattternId);
    }
    return postIds;
}

const InitPatternPreview = ({ element }) => {
    const [posts, setPosts] = useState();

    const postIds = PatternsStoreGetPostIdsFromPreviewButtons(element);

    useEffect(() => {
        const getPosts = async () => {
            const gotPosts = await PatternsStoreGetCurrentPageItems({
                postIds,
            });
            setPosts(gotPosts);
        };
        getPosts();
    }, [postIds]);

    if (!posts) {
        return <ModalLoading />;
    }

    const currentPattern = posts.find(
        (obj) => obj.id === parseInt(element.getAttribute('data-id'))
    );

    return (
        <PatternPreview
            posts={posts}
            pattern={currentPattern}
        />
    );
};
const PatternsStorePatternPreviewInit = () => {
    const containers = document.querySelectorAll('.pattern-store-button-preview');
    if (containers) {
        containers.forEach((element) => {
            element.disabled = false;
            element.onclick = () => {
                // Add element to dom/body
                const patternStoreDynamicDiv = PatternsStoreDynamicElement();
                document.body.appendChild(patternStoreDynamicDiv);

                // Render the component into the dynamic element
                render(
                    <InitPatternPreview element={element} />,
                    patternStoreDynamicDiv
                );
            };
        });
    }
};
window.addEventListener('load', PatternsStorePatternPreviewInit);
