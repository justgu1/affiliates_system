import { createApp } from 'vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { library } from '@fortawesome/fontawesome-svg-core';
import { faCaretDown, } from '@fortawesome/free-solid-svg-icons';
import './style.css';
import '@vuepic/vue-datepicker/dist/main.css'

import App from './App.vue';
import router from './router.js';

library.add(faCaretDown);

const app = createApp(App);
app.component('FontAwesomeIcon', FontAwesomeIcon);
app.use(router).mount('#app');