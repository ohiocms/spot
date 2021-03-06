import shared from 'belt/spot/js/amenity-spots/shared';
import edit from 'belt/spot/js/amenity-spots/edit';
import html from 'belt/spot/js/amenity-spots/list/template.html';

export default {
    mixins: [shared],
    props: {
        entity_type: {
            default: function () {
                return this.$parent.entity_type;
            }
        },
        entity_id: {
            default: function () {
                return this.$parent.entity_id;
            }
        },
    },
    computed: {
        chunks() {

            let size = 1;
            let length = this.parentAmenities.length;

            if (length) {
                size = Math.ceil(length / 4);
            }

            return _.chunk(this.parentAmenities, size);
        },
    },
    components: {
        edit: edit,
    },
    template: html
}