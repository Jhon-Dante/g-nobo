import Vue from 'vue'
import Vuex from 'vuex'
import wholesalers from './modules/wholesalers'

Vue.use(Vuex)

export default new Vuex.Store({
  modules: {
    wholesalers
  },
})