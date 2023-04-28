require('./js/bootstrap');

import 'ant-design-vue/dist/antd.css';
import { createApp } from 'vue';
import Antd from 'ant-design-vue';
import EditVue from './js/components/EditVue.vue';
import CreateVue from './js/components/CreateVue.vue';
import EnrollmentsVue from './js/components/EnrollmentsVue.vue';

createApp({
    components: {
        EditVue,
        CreateVue,
        EnrollmentsVue
    }
}).use(Antd).mount('#app');