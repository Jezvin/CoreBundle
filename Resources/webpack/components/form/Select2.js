class Select2 {

    constructor($elt) {
        this.$view = $elt;
        this.init();
    }

    configureOptions() {
        let data_options = this.$view.data('options');

        this.options = data_options ? JSON.parse(Utils.decode_html(data_options)) : {};
        this.s2_options = this.options['select2'] ? this.options['select2'] : {};

        // renderer
        if (this.options['template']) {
            let $template = $(this.options['template']);

            if ($template.length === 1) {
                let _template = _.template($template.html());
                this.s2_options['templateResult'] = (state) => {
                    return state.id
                        ? $('<span>' + _template({
                                'id': state.id,
                                'text': state.text,
                                'extra': state.extra || $(state.element).data()
                            }) + '</span>')
                        : state.text;
                }
            } else {
                console.error("No template found with selector " + this.options['template']);
            }
        }

        // ajax loading
        if (this.options['ajax_url']) {

            this.s2_options['ajax'] = {
                url: this.options['ajax_url'],
                dataType: 'json',
                delay: 250,
                data: function (params) {
                    return {q: params.term, page: params.page}
                },
                processResults: function (data, params) {
                    let more = data.more || false;
                    return {
                        results: data.items,
                        pagination: {
                            more: more
                        }
                    }
                },
                cache: true
            }
        }
    }

    init() {
        this.configureOptions();
        this.$view.select2(this.s2_options);
    }
}

module.exports = Select2;