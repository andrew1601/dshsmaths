<template>
    <div class="navbar navbar-expand-lg navbar-dark bg-dark">
        <a class="navbar-brand" href="/">
            <img src="/img/dshs_badge_white.png" width="32" alt="DSHS Logo" class="mr-2">
            DSHS Maths
        </a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarContent" aria-controls="navbarContent" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarContent">
            <ul class="navbar-nav">
                <li class="nav-item">
                    <inertia-link href="/data-entry" :class="{'nav-link': true, 'active': route().current('data-entry.*')}">Data Entry</inertia-link>
                </li>
                <li class="nav-item">
                    <inertia-link href="/analysis" :class="{'nav-link': true, 'active': route().current('analysis.*')}">Analysis</inertia-link>
                </li>
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navExportDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Export
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navExportDropdown">
                        <inertia-link href="/export/analysis" :class="{'dropdown-item': true, 'active': route().current('export.analysis.*')}">Analysis Sheets</inertia-link>
                        <inertia-link href="/export/yellow-sheets" :class="{'dropdown-item': true, 'active': route().current('export.yellow-sheets.*')}">Yellow Sheets</inertia-link>
                    </div>
                </li>
            </ul>
            <ul class="navbar-nav ml-auto">
                <li class="nav-item dropdown">
                    <a href="#" class="nav-link dropdown-toggle" id="navManageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        Manage
                    </a>
                    <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navManageDropdown">
                        <inertia-link href="/arbor-import" :class="{'dropdown-item': true, 'active': route().current('arbor-import.*')}">Arbor Sync</inertia-link>
                        <inertia-link href="/teaching-groups" :class="{'dropdown-item': true, 'active': route().current('teaching-groups.*')}">Teaching Groups</inertia-link>
                        <inertia-link href="/tests" class="dropdown-item" :class="{'dropdown-item': true, 'active': route().current('tests.*')}">Tests</inertia-link>
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
