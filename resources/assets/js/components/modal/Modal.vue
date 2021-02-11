<template id="template-modal">
    <div :id="this.id" class="modal">
        <div class="modal-content row">
            <h4 v-if="title != ''">{{ title }}</h4>
            <slot></slot>
        </div>
        <div class="modal-footer">
            <template v-if="confirm">
                <a href="#!" class="modal-close btn-flat">Cancelar</a>
                <a href="#!" v-on:click="_accept" class="btn-flat">{{ btnOk }}</a>
            </template>
            <template v-else>
                <a href="#!" class="modal-close btn-flat">{{ btnOk }}</a>
            </template>
        </div>
    </div>
</template>

<style lang="scss">

</style>


<script>
export default {
    template: "#template-modal",
    props: {
        confirm: {
            type: Boolean,
            default: false
        },
        'btn-ok': {
            type: String,
            default: "Aceptar"
        },
        title: {
            type: String
        },
        id: {
            type: String,
            default: "modal1"
        }
    },
    methods:{
        _accept(){
            this.$emit('pressok', true);
        }
    }
}
</script>
