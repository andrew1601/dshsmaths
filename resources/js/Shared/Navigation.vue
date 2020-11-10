<template>
    <div class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="#">
            <img src="/img/dshs_badge_white.png" width="32" alt="DSHS Logo" class="mr-2">
            DSHS Maths
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navManageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Manage
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navManageDropdown">
                        <inertia-link href="/arbor-import" class="dropdown-item">Arbor Sync</inertia-link>
                        <inertia-link href="/teaching-groups" class="dropdown-item">Teaching Groups</inertia-link>
                    </div>
                </li>
            </ul>
        </div>
    </div>
</template>

<script>
export default {
    name: "Navigation"
}
</script>

<style scoped>


</style>
