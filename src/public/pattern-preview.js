/* WordPres */
import { __ } from '@wordpress/i18n';
import { render, useState, useEffect } from '@wordpress/element';

/* Library */

/* Atrc */

/* Internal */
import PatternsStoreDynamicElement from '../utils/dynamic-div';
import PatternPreview from '../admin/pages/patterns/components/pattern-preview';
import { AtrcRemoveDoubleSlashesFromUrl } from '../admin/pages/patterns/utils';
import { ModalLoading } from '../admin/components/molecules';

/* Local */
const patternRestUrl = AtrcRemoveDoubleSlashesFromUrl(
    PatternsStoreLocalize.rest_url + PatternsStoreLocalize.post_type_rest_url
);

window.PatternsStoreGlobalItems = {};
const PatternsStoreGlobalRelatedItems = {};
async function PatternsStoreGetCurrentPageItems({ currentPattern, postIds, restUrl }) {
    try {
        const cacheKey =
            postIds && postIds.length ? 'ps-' + postIds.join('-') : null;

        if (!cacheKey) {
            return null;
        }

        if (!PatternsStoreGlobalRelatedItems.hasOwnProperty(cacheKey)) {
            const urlObject = new URL(restUrl ? restUrl : patternRestUrl);
            urlObject.searchParams.append('include', postIds.join(','));
            urlObject.searchParams.append('orderby', 'include');
            urlObject.searchParams.append('per_page', postIds.length);
            const response = await fetch(urlObject.toString(), {
                method: 'GET',
                headers: {
                    'X-WP-Nonce': PatternsStoreLocalize.nonce
                }
            });

            if (!response.ok) {
                throw new Error(__('Error fetching posts:', 'patterns-store') + `${response.statusText}`);
            }

            const data = await response.json();

            /* 
            * Set these items in window.PatternsStoreGlobalItems for global access
            */
            if (data) {
                data.forEach(item => {
                    if (item.id) {
                        window.PatternsStoreGlobalItems[item.id] = item;

                        if (item.patterns && Array.isArray(item.patterns)) {
                            item.patterns.forEach(pattern => {
                                if (pattern.id) {
                                    window.PatternsStoreGlobalItems[pattern.id] = pattern;
                                }
                            });
                        }

                        if (item['pattern-kit'] && item['pattern-kit'].id) {
                            window.PatternsStoreGlobalItems[item['pattern-kit'].id] = item['pattern-kit'];
                        }
                    }
                });
            }

            // Clone the data to create newData for processing references
            const newData = JSON.parse(JSON.stringify(data)); // Deep clone using JSON methods

            data.forEach((item, iDx) => {
                // Check and replace with reference-id if exists
                if (item['reference-id'] && window.PatternsStoreGlobalItems.hasOwnProperty(item['reference-id'])) {
                    newData[iDx] = window.PatternsStoreGlobalItems[item['reference-id']];
                }

                // Handle patterns in each item
                if (item.patterns && Array.isArray(item.patterns)) {
                    item.patterns.forEach((pattern, iDx1) => {
                        if (pattern['reference-id'] && window.PatternsStoreGlobalItems.hasOwnProperty(pattern['reference-id'])) {
                            if (!newData[iDx]['patterns']) {
                                newData[iDx]['patterns'] = [];
                            }
                            newData[iDx]['patterns'][iDx1] = window.PatternsStoreGlobalItems[pattern['reference-id']];
                        }
                    });
                } else if (item['pattern-kit']) {
                    // Handle pattern kit reference
                    if (item['pattern-kit']['reference-id'] && window.PatternsStoreGlobalItems.hasOwnProperty(item['pattern-kit']['reference-id'])) {
                        newData[iDx]['pattern-kit'] = window.PatternsStoreGlobalItems[item['pattern-kit']['reference-id']];
                    }
                }
            });

            /* Caching */
            PatternsStoreGlobalRelatedItems[cacheKey] = newData;
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

                const response = await fetch(urlObject.toString(), {
                    method: 'GET',
                    headers: {
                        'X-WP-Nonce': PatternsStoreLocalize.nonce
                    }
                });
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
            '.patterns-store-button-preview'
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
            let restUrl = '';
            if (element.getAttribute('data-rest-url')) {
                restUrl = AtrcRemoveDoubleSlashesFromUrl(
                    PatternsStoreLocalize.rest_url + element.getAttribute('data-rest-url')
                );
            }
            const gotPosts = await PatternsStoreGetCurrentPageItems({
                postIds,
                restUrl
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
    const previewButtons = document.querySelectorAll('.patterns-store-button-preview');
    if (previewButtons) {
        previewButtons.forEach((element) => {
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