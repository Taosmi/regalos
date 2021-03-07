    /**
     * Remove all the sibling nodes of a node.
     *
     * @param item  A DOM node.
     */
var removeSiblings = function (node) {
        while (node.nextSibling) {
            node.parentNode.removeChild(node.nextSibling);
        }
    },

    /**
     * Remove all the child nodes with an attribute.
     *
     * @param parent  A DOM node as a parent.
     * @param attr    An attribute to search by.
     */
    removeChildByAttr = function (parent, attr) {
        var node = parent.firstChild;
        while (node) {
            if ((node.nodeType === 1) && node.hasAttribute(attr)) {
                node = node.nextSibling;
                parent.removeChild(node.previousSibling);
            } else {
                if (node.hasChildNodes()) {
                    removeChildByAttr(node, attr);
                }
                node = node.nextSibling;
            }
        }
    },

    /**
     *
     * @param nodeId
     * @param data
     */
    iterator = function (nodeId, data) {
        var i, node = document.getElementById(nodeId), clone,
            fragment = document.createDocumentFragment();
        // Hide the template node.
        node.setAttribute('style', 'display:none');
        // Create the fragment with the new nodes.
        for (i in data) {
            // Create a new node.
            clone = node.cloneNode(false);
            clone.removeAttribute('style');
            clone.innerHTML = node.innerHTML.replace(/\{(!)?(\w+)\}/g, function (match, code, value) {
                if (code && !data[i][value]) {
                    return '" data-sup="yes';
                }
                return data[i][value] || '';
            });
            // Erase all the tags with the data-sup attribute.
            removeChildByAttr(clone, 'data-sup');
            fragment.appendChild(clone);
        }
        // Erase the old content preserving the template node.
        removeSiblings(node);
        // Append the new nodes.
        node.parentNode.appendChild(fragment);
    },

    splitArray = function (data, num) {
        var i, ln = data.length, splitData = [];
        // Initialize the arrays.
        for (i = 0; i < num; i += 1) {
            splitData[i] = [];
        }
        // Split the array.
        for (i = 0; i < ln; i += 1) {
            splitData[(i%num)].push(data[i]);
        }
        return splitData;
    };