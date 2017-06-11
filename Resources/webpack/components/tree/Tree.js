require('./tree.scss');

class Tree {

    constructor($elt, options) {
        this.$view = $elt;
        this.$tree = this.$view.find('.js-umbrella-tree');

        this.options = options;

        this.configureOptions();
        this.init();
        this.bind();
    }

    configureOptions() {
        let defaultOptions = {
            collapsable: true,
            start_expanded: true,
        };

        this.options = {...defaultOptions, ...this.options};
    }

    init() {
        this.$tree.nestedSortable({
            forcePlaceholderSize: true,
            handle: '.node-content',
            items: 'li',
            toleranceElement: '> div',
            isTree: true,
            startCollapsed: !this.options['start_expanded'],
            relocate: (e, object) => {
                let prev_node_id, parent_node_id, node_id;

                let $node = $(object.item[0]).closest('li');

                let $root = $node.closest('ol');
                let $parent = $node.parent().closest('li');
                let $prev_node = $node.prev();

                node_id = $node.data('id');
                if ($prev_node.length) {
                    prev_node_id = $prev_node.data('id');
                } else if($parent.length) {
                    parent_node_id = $parent.data('id');
                } else if ($root.length) {
                    parent_node_id = $root.data('root-id');
                }

                let params = { prev_node_id, parent_node_id, node_id};

                if (this.options['ajax_relocate_url']) {
                    Api.ajax(this.options['ajax_relocate_type'], this.options['ajax_relocate_url'], params);
                }
            }
        });
    }

    bind() {
        this.$tree.on('click', '.js-collapse-handle', (e) => {
            let $target = $(e.currentTarget);
            $target.closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
        });
    }



}

module.exports = Tree;