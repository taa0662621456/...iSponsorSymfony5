import '../stimulus_bridge_init'
import 'bootstrap'
//import 'bootstrap/dist/js/bootstrap.bundle.min'
import { Toast } from 'bootstrap/dist/js/bootstrap.esm.min'


//require('bootstrap-autohide-navbar');

// require('smartwizard');

//require('masonry-layout');
require('../scss/homepage.scss');
require('../css/homepage.css');


//import('./masonry_init.js');
//import('../css/app.css');
// import('../css/navbar.css');


// //require('../css/likeMasonryCart.css');
require('../fontawesome-pro/js/all.min');
// require('./auto_hiding_navbar_init');
// require('./bootstrap-tags-input-init');
// require('./cart.js');
// //require('./multistep_form');
// require('./tinymce_init');
// require('./smartwizard_init');
require('./move_up');
// //require('./masonry_init');
// require('./cols_per_row');
// require('./add-collection-widget');
require('./full_screen');

Array.from(document.querySelectorAll('.toast'))
    .forEach(toastNode => new Toast(toastNode).show());
