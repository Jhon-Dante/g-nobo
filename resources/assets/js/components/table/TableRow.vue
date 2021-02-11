<template id="template-table-row">
    <tr>
        <slot></slot>
    </tr>
</template>


<script>
export default {
    template: "#template-table-row",
    props: {
        data: {
            type: Object,
            default(){
                return {};
            }
        }
    },
    data() {
        return {
            td: {}
        }
    },
    methods: {
        
    }
}
</script>