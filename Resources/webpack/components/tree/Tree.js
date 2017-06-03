require('./tree.scss');

class Tree {

    constructor(view_selector, options) {
        this.$view = $(view_selector);
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
            startCollapsed: !this.options['start_expanded']
        });
    }

    bind() {
        this.$tree.on('click', '.js-collapse-handle', function(e) {
            $(this).closest('li').toggleClass('mjs-nestedSortable-collapsed').toggleClass('mjs-nestedSortable-expanded');
            $(this).toggleClass('collapsed').toggleClass('expanded');
        });
    }

}

module.exports = Tree;