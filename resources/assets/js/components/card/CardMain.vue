<template id="template-card-content">
    <div :id="id" class="card">
        <slot></slot>
    </div>
</template>

<script>
export default {
    template: "#template-card-content",
    props: {
        id: {
            type: String,
            default: ""
        }
    }
}
</script>
