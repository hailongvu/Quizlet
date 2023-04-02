<template>
    <div class="card position-sticky top-1 mt-4">
        <ul class="nav flex-column bg-white border-radius-lg p-3">
            <li class="nav-item pt-2">
                <a class="nav-link text-body d-flex align-items-center" data-scroll href="#info">
                    <i class="fa-solid fa-circle-info me-2 text-dark opacity-6"></i>
                    <span class="text-sm">Info</span>
                </a>
            </li>
            <li class="nav-item pt-2">
                <a class="nav-link text-body d-flex align-items-center" data-scroll href="#staff">
                    <i class="fa-duotone fa-clipboard-user me-2 text-dark opacity-6"></i>
                    <span class="text-sm">Staff</span>
                </a>
            </li>
            <li class="nav-item pt-2">
                <a class="nav-link text-body d-flex align-items-center" data-scroll href="#class">
                    <i class="fa-regular fa-screen-users me-2 text-dark opacity-6"></i>
                    <span class="text-sm">Class</span>
                </a>
            </li>
            <li class="nav-item pt-2">
                <a class="nav-link text-body d-flex align-items-center" data-scroll href="#student">
                    <i class="fa-solid fa-graduation-cap me-2 text-dark opacity-6"></i>
                    <span class="text-sm">Student</span>
                </a>
            </li>
            <li class="nav-item pt-2">
                <a class="nav-link text-body d-flex align-items-center" data-scroll href="#teacher">
                    <i class="fa-duotone fa-chalkboard-user me-2 text-dark opacity-6"></i>
                    <span class="text-sm">Teacher</span>
                </a>
            </li>
        </ul>
    </div>
</template>

<script>
export default {
    name: "Menu"
}
</script>

<style scoped>

</style>
