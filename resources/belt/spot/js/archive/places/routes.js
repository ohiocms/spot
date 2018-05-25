import index from 'belt/spot/js/places/ctlr/index';
import create from 'belt/spot/js/places/ctlr/create';
import edit  from 'belt/spot/js/places/ctlr/edit';
import addresses  from 'belt/spot/js/places/ctlr/addresses';
import amenities  from 'belt/spot/js/places/ctlr/amenities';
import attachments  from 'belt/spot/js/places/ctlr/attachments';
import params  from 'belt/spot/js/places/ctlr/params';
import sections  from 'belt/spot/js/places/ctlr/sections';
import terms  from 'belt/spot/js/places/ctlr/terms';

export default [
    {path: '/places', component: index, canReuse: false, name: 'places'},
    {path: '/places/create', component: create, name: 'places.create'},
    {path: '/places/edit/:id', component: edit, name: 'places.edit'},
    {path: '/places/edit/:id/addresses/:address?', component: addresses, name: 'places.addresses'},
    {path: '/places/edit/:id/amenities', component: amenities, name: 'places.amenities'},
    {path: '/places/edit/:id/attachments', component: attachments, name: 'places.attachments'},
    {path: '/places/edit/:id/sections/:section?', component: sections, name: 'places.sections'},
    {path: '/places/edit/:id/terms', component: terms, name: 'places.terms'},
    {path: '/places/edit/:id/params', component: params, name: 'places.params'},
]