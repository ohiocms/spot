
// helpers
import Form from 'belt/spot/js/amenities/form';

// templates make a change

import edit_html from 'belt/spot/js/amenities/templates/edit.html';
import form_html from 'belt/spot/js/amenities/templates/form.html';

export default {
    components: {

        edit: {
            data() {
                return {
                    form: new Form(),
                }
            },
            mounted() {
                this.form.show(this.$route.params.id);
            },
            template: form_html,
        },
    },
    template: edit_html,
}