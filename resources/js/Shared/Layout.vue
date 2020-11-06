<template>
    <div>
        <app-navigation></app-navigation>
        <div class="container pt-3">
            <slot></slot>
        </div>
    </div>
</template>

<script>
import AppNavigation from './Navigation';

export default {
    name: "Layout",
    components: {
        AppNavigation
    }
}
</script>

<style scoped>

</style>
