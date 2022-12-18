import Vue from 'vue';
import VueRouter from 'vue-router';

/**
 * Top level route components.
 */
const Messages = () => import('./components/Messages');
const SendMessage = () => import('./components/SendMessage');

/**
 * Register router with vue.
 */
Vue.use(VueRouter);

/**
 * Create vue router and register top level routes.
 */
const router = new VueRouter({
    mode: 'history',
    routes: [
        {
            path: '/',
            name: 'messages',
            component: Messages
        },
        {
            path: '/messages/send',
            name: 'send-message',
            component: SendMessage
        },
    ]
});

export default router;
