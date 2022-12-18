import Vue from 'vue';
import Vuex from 'vuex';
import settings from './settings';

Vue.use(Vuex);

/**
 * Setup vuex store and register modules.
 */
const store = new Vuex.Store({
  modules: {
    settings
  },
});

export default store;
