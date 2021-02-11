<template id="template-card-title">
    <div class="card-title form-title">
        <slot></slot>
    </div>
</template>

<style scoped>
    .card .card-title.form-title{
        margin: 0 !important;
    }
</style>


<script>
export default {
    template: "#template-card-title"
}
</script>
