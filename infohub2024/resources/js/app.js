import './bootstrap';

import 'jquery';
import $ from 'jquery';
window.$ = window.jQuery = $;

import 'bootstrap';
import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap/dist/js/bootstrap.bundle.min';
import 'bootstrap/dist/js/bootstrap.bundle.min.js';

import 'jstree';
import 'jstree/dist/themes/default/style.min.css';
import 'jstree/dist/themes/default/style.min.css';

import 'dropzone'
import 'dropzone/dist/dropzone.css';
import Dropzone from 'dropzone';
window.Dropzone = Dropzone;

import 'jquery-validation';

import 'nprogress';
import NProgress from 'nprogress';
import 'nprogress/nprogress.css';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// NProgress.configure({
//     showSpinner: false,
//     trickleSpeed: 200,
//     easing: 'ease',
//     parent: '#custom-progress-bar',
//     speed: 500
// });
//

// Bind NProgress to global AJAX events
// $(document).ajaxStart(NProgress.start);
// $(document).ajaxStop(NProgress.done);

document.addEventListener('DOMContentLoaded', () => {
    import('./global/menu_group_permissions')
    import('./menu_tree');
    import('./menu_delete');
    import('./concessios_component_validation');
    // import('./file_upload_validation');
    import('./menu_tree_files_upload');
    import('./file_upload');
    import('./upload-delete-file');
    import('./users');
    import('./menu_tree_permissions_group');
    import('./menu_tree_permissions_user');
    import('./accessRequest_validation');
    import('./menu_tab_create_validation');
    import('./menu_tree_user_notifications');
    import('./user_permissions_copy');
    import('./statistics_component');
    import('./login_validation');
    import('./progress_bar');
    import('./owners_pickers');
    import('./forgot_password_form');
    // import('./concessios_component_validation');
});
