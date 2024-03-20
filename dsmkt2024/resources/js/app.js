import './bootstrap';

import 'jquery';
import $ from 'jquery';
window.$ = window.jQuery = $;

// window.$ = window.jQuery = $;

import 'jstree';
import 'jstree/dist/themes/default/style.min.css';



import 'jstree/dist/themes/default/style.min.css';

// import './menu_tree';

import Alpine from 'alpinejs';

window.Alpine = Alpine;

Alpine.start();


document.addEventListener('DOMContentLoaded', () => {
    import('./menu_tree');
});
