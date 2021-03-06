import Form from 'belt/spot/js/deals/form';

import datetimeInput from 'belt/core/js/inputs/datetime';
import form_html from 'belt/spot/js/deals/templates/form.html';
import create_html from 'belt/spot/js/deals/templates/create.html';

export default {
    components: {
        create: {
            data() {
                return {
                    deal: new Form({router: this.$router}),
                }
            },
            components: {

                datetimeInput,
            },
            template: form_html,
        },
    },
    template: create_html
}