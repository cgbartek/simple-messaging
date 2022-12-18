/**
 * Viewport module for vuex store.
 *
 * Holds current viewport state based on user ui preference.
 */
export default {
    namespaced: true,
    state: {
        darkMode: false
    },
    getters: {
        isDarkMode: state => state.darkMode === true
    },
    mutations: {
      updateDarkMode(state, value){
       state.darkMode = value
      }
    }
};
