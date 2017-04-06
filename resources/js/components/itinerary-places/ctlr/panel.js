import shared from './shared';
import self from './panel';

// templates
import panel_html from '../templates/panel.html';

export default {
    mixins: [shared],
    props: ['itineraryPlace'],
    computed: {
        panelMode() {
            if (this.moving.id) {
                if (this.itineraryPlace.id == this.moving.id) {
                    return 'is-moving';
                }
                return 'is-watching';
            }
            return 'default';
        }
    },
    methods: {
        destroy(id) {
            this.active.setData({id: id});
            this.active.destroy(id)
                .then(() => {
                    this.itineraryPlaces.index();
                });
        },
        cancel() {
            this.reset();
        },
        insert(id, position) {
            this.creating.show = true;
            this.creating.neighbor_id = id;
            this.creating.position = position;
        },
        setMoving(id) {
            this.moving.show(id);
        },
    },
    template: panel_html
}