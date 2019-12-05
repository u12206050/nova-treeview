<template>
    <TreeContainer :resource="$route.query.resource" />
</template>

<script>
import TreeContainer from './TreeContainer'
export default {
    components: {
        TreeContainer
    },
    mounted() {
        if (!this.$route.query.resource) {
            console.warn(this.$route.query)
            this.$router.replace('/404')
        }
    }
}
</script>