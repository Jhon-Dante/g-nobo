import wholesalerService from '../../services/wara/wholesaler'

const state = {
    all: [],
    option: 1,
    sending: false,
    errMessage: '',
    selected: {}
}

const getters = {}

const actions = {
    changeOption ({ commit }, option) {
        commit('setOption', option)
    },

    addWholesaler ({commit}, data) {
        commit('setSending', true)
        showLoading()     
        wholesalerService.store(data)
            .then(wholesalerSaved => {
                swal('', 'Se registro la colección correctamente', 'success')
                commit('add', wholesalerSaved)
                commit('setOption', 1)
                location.reload()
            })
            .catch(err => {
                quiLoading()
                if(err.response.status === 422) {
                    swal('', err.response.data.error, 'error')
                }else {
                    swal('',  'Algo ha ocurrido, intente nuevamente', 'error')
                }
                commit('setSending', false)
            }) .then(all => {
               
            })
    },

    deleteWholesaler ({ commit }, id) {
        wholesalerService.destroy(id)
            .then(res => {
                commit('delete', id)
                swal('', 'Se eliminó la colección correctamente', 'success')
                console.log(res)
            })
            .catch(err => {
                swal('',  'Algo ha ocurrido, intente nuevamente', 'error')
                console.log(err)
            })
    }
}

const mutations = {
    setWholesalers (state, wholesalers) {
        state.all = wholesalers
    },

    setOption (state, option) {
        state.option = option
    },

    add (state, wholesaler) {
        state.all.push(wholesaler)
    },

    setSaveStatus (state, status) {
        state.savedStatus = status
    },

    delete (state, id) {
        console.log(id)
        let obj = state.all.find(wholesaler => wholesaler.id == id)
        console.log(obj)
        state.all.splice(state.all.indexOf(obj), 1)
    },

    setSelected (state, id) {
        wholesaler = state.all.find(wholesaler => wholesaler.id == id)
        state.selected = wholesaler
        console.log(wholesaler)
    },

    setSending (state, status) {
        state.sending = status
    }
}

export default {
    namespaced: true,
    state,
    getters,
    actions,
    mutations
}