<template>
    <layout>
        <h2><img src="/img/arbor-icon.png" width="32" alt="Arbor Icon" class="mr-2">Arbor Import Wizard</h2>

        <div class="progress my-3" style="height: 30px;">
            <div class="progress-bar bg-success" style="width: 100%"></div>
        </div>

        <div class="card">
            <div class="card-body wizard-step bg-success d-flex flex-column justify-content-center align-items-center">
                <svg width="10em" height="10em" viewBox="0 0 16 16" class="bi bi-check-circle-fill text-white" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                    <path fill-rule="evenodd" d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0zm-3.97-3.03a.75.75 0 0 0-1.08.022L7.477 9.417 5.384 7.323a.75.75 0 0 0-1.06 1.06L6.97 11.03a.75.75 0 0 0 1.079-.02l3.992-4.99a.75.75 0 0 0-.01-1.05z"/>
                </svg>
                <h4 class="mt-3 text-white">
                    DONE
                </h4>
                <p class="text-white">Your data has been successfully imported!</p>
                <inertia-link class="btn btn-outline-light" href="/">Home</inertia-link>
            </div>
        </div>
    </layout>
</template>

<script>
export default {
    name: "Done"
}
</script>

<style scoped>

.wizard-step {
    height: 50vh;
    max-height: 50vh;
    overflow-y: scroll;
}

</style>
