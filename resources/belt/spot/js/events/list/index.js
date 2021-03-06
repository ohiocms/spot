import Form from 'belt/spot/js/events/form';
import Table from 'belt/spot/js/events/table';

import index_html from 'belt/spot/js/events/list/template.html';


import datetime from 'belt/core/js/mixins/datetime';
import datetimeInput from 'belt/core/js/inputs/datetime';

export default {

    components: {

        index: {
            mixins: [datetime],
            data() {
                return {
                    table: new Table({router: this.$router}),
                }
            },
            mounted() {
                this.table.updateQueryFromRouter();
                this.table.index();
            },
            methods: {
                filter: _.debounce(function (query) {
                    if (query) {
                        query.page = 1;
                        this.table.updateQuery(query);
                    }
                    this.table.index()
                        .then(() => {
                            //this.table.pushQueryToHistory();
                            this.table.pushQueryToRouter();
                        });
                }, 750),
                copy(id) {
                    let form = new Form();
                    form.service.baseUrl = '/api/v1/events/?source=' + id;
                    form.router = this.$router;
                    form.submit();
                }
            },
            components: {
                datetimeInput,
                
                
            },
            template: index_html,
        },
    },

    template: `
        <div>
            <heading>
                <span slot="title">Event Manager</span>
                <span slot="help"><link-help docKey="admin.spot.events.manager" /></span>
                <li><router-link :to="{ name: 'events' }">Event Manager</router-link></li>
            </heading>
            <section class="content-subheader">
                <p class="text-muted">{{ trans('belt-spot::events.manager.overall') }}</p>
            </section>
            <section class="content">
                <index></index>
            </section>
        </div>
        `
}