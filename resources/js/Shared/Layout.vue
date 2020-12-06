<template>
    <div>
        <app-navigation></app-navigation>
        <div :class="{'container': this.container, 'mx-3': !this.container ,'pt-3': true }">
            <slot></slot>
        </div>
    </div>
</template>

<script>
import AppNavigation from './Navigation';

export default {
    name: "Layout",
    props: {
        container: {
            default: true
        }
    },
    components: {
        AppNavigation
    }
}
</script>

<style scoped>

</style>
