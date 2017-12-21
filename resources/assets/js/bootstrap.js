/*
| -------------------------------------------------------------------------
| #SETUP
| -------------------------------------------------------------------------
*/



// #JQUERY
// ========================================================================

try {
    window.$ = window.jQuery = require('jquery');
} catch (e) {}



// #AXIOS
// ========================================================================

window.axios = require('axios');
window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';




// #SIMPLE MDE
// =========================================================================

window.SimpleMDE = require('simplemde');



// #VUE
// ========================================================================

// window.Vue = require('vue');

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// Vue.component('example', require('./components/Example.vue'));

// const app = new Vue({
//     el: '#app'
// });