<template id="template-table-head">
    <th>
        <slot></slot>
    </th>
</template>


<script>
export default {
    template: "#template-table-head",
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

        }
    },
    methods: {
        
    }
}
</script>