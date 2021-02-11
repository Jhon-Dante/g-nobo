<template id="template-table-cell">
    <td>
        <slot></slot>
    </td>
</template>

<script>
export default {
    template: "#template-table-cell",
    props: {
        data: {
            type: Object,
            default(){
                return {};
            }
        },
        'byte-level': {
            type: String,
            default: ""
        }
    },
    data() {
        return {

        }
    },
    methods: {
        
    },
    mounted(){
        // let th = document.createElement("th");
        // th.innerText = this.byteLevel;
        let th = {
            name: this.byteLevel
        }

        // console.log(this.$parent.$parent.thead, this.$parent.$parent.thead.includes(th), 'table cell');

        // if(!this.$parent.$parent.thead.includes(th)){
        //     this.$parent.$parent.thead.push(th)
        // }
        // .push({name: this.byteLevel});
        // document.querySelector(`#${this.$parent.$parent.id} thead tr`).appendChild(th);
    }
}
</script>