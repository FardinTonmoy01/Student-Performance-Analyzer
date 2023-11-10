
import Vue from 'vue';
import VueRouter from "vue-router";
import { BootstrapVue } from 'bootstrap-vue'
import CxltToastr from 'cxlt-vue2-toastr'
import CreateUpdate from "./components/marks-entry/CreateForm.vue";
import ExcelForm from "./components/marks-entry/ExcelForm.vue";

// Import Bootstrap and BootstrapVue CSS files (order is important)
import 'bootstrap/dist/css/bootstrap.css'
import 'bootstrap-vue/dist/bootstrap-vue.css'

import 'cxlt-vue2-toastr/dist/css/cxlt-vue2-toastr.css'

// Toastr config
const toastrConfigs = {
  position: 'top right',
  errorColor: '#D6E09B',
  warningColor: '#218838',
  showMethod: 'headShake',
  hideMethod: 'headShake',
  showDuration: 1000,
  hideDuration: 0,
  timeOut: 3000
}

Vue.use(CxltToastr, toastrConfigs)
// Make BootstrapVue available throughout your project
Vue.use(BootstrapVue)
Vue.use(VueRouter);

window.axios = require('axios');

const MarksEntry = () => import(
    './components/Route.vue'
    );
let routes = [
    {
        path: '/marks-entry/add',
        component: CreateUpdate,
    },
    {
        path: '/marks-entry/edit/:id',
        component: CreateUpdate,
    },
    {
        path: '/marks-entry/import-excel',
        component: ExcelForm,
    }
];
let router = new VueRouter({
    mode: 'history',
    routes,
});
new Vue({
    router,
    components: {MarksEntry},
    render: h => h(MarksEntry)
}).$mount("#marks-entry");