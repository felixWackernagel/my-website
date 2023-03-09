import './../css/app.css';

import './bootstrap';

import 'bootstrap/dist/css/bootstrap.min.css';
import 'bootstrap';
import 'bootstrap-icons/font/bootstrap-icons.css';

import Swal from 'sweetalert2/dist/sweetalert2.js';
import 'sweetalert2/dist/sweetalert2.css';

window.Swal = Swal;
window.Toast = Swal.mixin( {
    toast: true,
    position: 'top-end',
    showConfirmButton: false,
    timer: 3000,
    timerProgressBar: true,
    didOpen: (toast) => {
        toast.addEventListener( 'mouseenter', Swal.stopTimer );
        toast.addEventListener( 'mouseleave', Swal.resumeTimer );
      }
} );

import { createApp } from 'vue';

import { createPinia } from 'pinia';
const pinia = createPinia();

import app from './components/app.vue';

import router from './router';

createApp( app ).use( pinia ).use( router ).mount( '#app' );