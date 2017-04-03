import index from './ctlr/index';
import create from './ctlr/create';
import edit  from './ctlr/edit';
import amenities  from './ctlr/amenities';
import categories  from './ctlr/categories';
import attachments  from './ctlr/attachments';
import sections  from './ctlr/sections';
import tags  from './ctlr/tags';

export default [
    {path: '/places', component: index, canReuse: false, name: 'places'},
    {path: '/places/create', component: create, name: 'places.create'},
    {path: '/places/edit/:id', component: edit, name: 'places.edit'},
    {path: '/places/edit/:id/amenities', component: amenities, name: 'places.amenities'},
    {path: '/places/edit/:id/attachments', component: attachments, name: 'places.attachments'},
    {path: '/places/edit/:id/categories', component: categories, name: 'places.categories'},
    {path: '/places/edit/:id/sections/:section?', component: sections, name: 'places.sections'},
    {path: '/places/edit/:id/tags', component: tags, name: 'places.tags'},
]