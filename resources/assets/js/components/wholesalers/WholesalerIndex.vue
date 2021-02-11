<template>
    <section class="container-fluid">
        <wholesaler-table v-show="option === 1"></wholesaler-table>
        <wholesaler-form v-show="option === 2"></wholesaler-form>
    </section>
</template>

<script>
import { mapState } from 'vuex'
import WholesalerTable from './WholesalerTable.vue'
import WholesalerForm from './WholesalerForm.vue'
import WholesalerEdit from './WholesalerEdit.vue'

export default {
    name: 'wholesaler-index',
     props: {
        wholesaler: {
            type: Array,
            default(){
                return[];
            }
        }
    },
    computed: mapState({
        option: state => state.wholesalers.option
    }),
    components: {
        WholesalerTable,
        WholesalerForm,
        WholesalerEdit
    },
    mounted () {
        console.log('se monto el componente')
        this.$store.commit('wholesalers/setWholesalers', this.wholesaler)
    }
}
</script>

