import shared from 'belt/spot/js/components/places/ctlr/shared';

// components
import tags from 'belt/glue/js/components/taggables/ctlr-edit';

export default {
    mixins: [shared],
    components: {
        tab: tags,
    },
}