/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/app.less';

import $ from 'jquery';
window.jQuery = $;
window.$ = $;
import '@fortawesome/fontawesome-free';
import 'bootstrap';
import 'jquery-bootgrid/dist/jquery.bootgrid.js';

import './core/custom.js';
// import './core/dashboard.js';
import './core/e3s.js';
import './core/nav.js';
import './core/options.js';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

