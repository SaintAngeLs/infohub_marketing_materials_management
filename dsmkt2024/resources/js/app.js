import './bootstrap';

import 'jquery';
import $ from 'jquery';
window.$ = window.jQuery = $;

// window.$ = window.jQuery = $;
// Import Bootstrap JS
import 'bootstrap';

// Import Bootstrap CSS
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

// import './menu_tree';

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();


document.addEventListener('DOMContentLoaded', () => {
    import('./menu_tree');
    import('./menu_delete');
    import('./menu_tree_files_upload');
    import('./file_upload');
    import('./upload-delete-file');
    import('./users');
});
