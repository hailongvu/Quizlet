<template>
    <!-- Navbar -->
    <nav
        class="navbar navbar-expand-lg top-0"
        :class="isBlur ? isBlur : 'shadow-none my-2 navbar-transparent'"
    >
        <div class="container-fluid p-0">
            <div style="height: 3rem">
                <router-link class="m-0 navbar-brand" to="/">
                    <img
                        src="/logo.png"
                        class="navbar-brand-img h-100"
                        alt="main_logo"
                    />
                    <span class="ms-2 font-weight-bold">Let Learn</span>
                </router-link>
            </div>
            <div>
                <button
                    class="shadow-none navbar-toggler"
                    type="button"
                    data-bs-toggle="collapse"
                    data-bs-target="#navigation"
                    aria-controls="navigation"
                    aria-expanded="false"
                    aria-label="Toggle navigation"
                >
                <span class="mt-2 navbar-toggler-icon">
                    <span class="navbar-toggler-bar bar1"></span>
                    <span class="navbar-toggler-bar bar2"></span>
                    <span class="navbar-toggler-bar bar3"></span>
                </span>
                </button>
            </div>
            <div
                id="navigation"
                class="pt-3 pb-2 collapse navbar-collapse w-100 py-lg-0">
                <div class="d-flex align-items-center ms-md-auto">
                    <div class="input-group">
                        <span class="input-group-text text-body">
                            <i class="fas fa-search" aria-hidden="true"></i>
                        </span>
                        <input type="text" class="form-control" :placeholder="'Type here to search...'"/>
                    </div>
                </div>
                <ul class="mx-auto navbar-nav navbar-nav-hover">
                    <li class="mx-2 nav-item dropdown dropdown-hover">
                        <a
                            id="dropdownMenuBlocks"
                            role="button"
                            class="cursor-pointer nav-link ps-2 d-flex justify-content-between align-items-center fs-6"
                            :class="[darkModes]"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >
                            Hello, {{ this.user.username }}
                            <span
                                class="arrow ms-1 d-block"
                                :class="darkMode ? 'd-lg-block' : 'd-lg-none'"
                            ><i class="fa-solid fa-caret-down"></i></span>
                        </a>
                        <div
                            class="p-3 mt-0 dropdown-menu dropdown-menu-animation dropdown-md dropdown-md-responsive border-radius-lg mt-lg-3"
                            aria-labelledby="dropdownMenuBlocks"
                        >
                            <div class="d-none d-lg-block">
                                <ul class="list-group">
                                    <li
                                        class="p-0 border-0 nav-item dropdown dropdown-hover dropdown-subitem list-group-item"
                                    >
                                        <router-link
                                            class="py-2 dropdown-item ps-3 border-radius-md"
                                            :to="{ name: 'home' }"
                                        >
                                            <div class="d-flex">
                                                <div class="h-10 mt-1 icon me-3 d-flex">
                                                    <i
                                                        class="fa-regular fa-user"
                                                    ></i>
                                                </div>
                                                <div
                                                    class="w-100 d-flex align-items-center justify-content-between"
                                                >
                                                    <div>
                                                        <router-link :to="{ name: 'home.profile'}"
                                                                     class="p-0 dropdown-header text-dark"
                                                                     aria-current="page">Profile
                                                        </router-link>
                                                    </div>
                                                </div>
                                            </div>
                                        </router-link>
                                    </li>
                                    <li
                                        class="p-0 border-0 nav-item dropdown dropdown-hover dropdown-subitem list-group-item"
                                    >
                                        <router-link
                                            class="py-2 dropdown-item ps-3 border-radius-md"
                                            :to="{ name: 'home' }"
                                        >
                                            <div class="d-flex">
                                                <div class="h-10 mt-1 icon me-3 d-flex">
                                                    <i
                                                        class="fa-regular fa-circle-info"
                                                    ></i>
                                                </div>
                                                <div
                                                    class="w-100 d-flex align-items-center justify-content-between"
                                                >
                                                    <div>
                                                        <p class="p-0 dropdown-header text-dark">Help Center</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </router-link>
                                    </li>
                                    <li
                                        class="p-0 border-0 nav-item dropdown dropdown-hover dropdown-subitem list-group-item"
                                    >
                                        <router-link
                                            class="py-2 dropdown-item ps-3 border-radius-md"
                                            :to="{ name: 'home' }"
                                        >
                                            <div class="d-flex">
                                                <div class="h-10 mt-1 icon me-3 d-flex">
                                                    <i class="fa-regular fa-gear"></i>
                                                </div>
                                                <div
                                                    class="w-100 d-flex align-items-center justify-content-between"
                                                >
                                                    <div>
                                                        <router-link :to="{ name: 'home.setting'}"
                                                                     class="p-0 dropdown-header text-dark"
                                                                     aria-current="page">Setting
                                                        </router-link>
                                                    </div>
                                                </div>
                                            </div>
                                        </router-link>
                                    </li>
                                    <li
                                        class="p-0 border-0 nav-item dropdown dropdown-hover dropdown-subitem list-group-item"
                                    >
                                        <a class="py-2 ps-3 border-radius-md" href="/auth/logout">
                                            <div class="d-flex">
                                                <div class="h-10 mt-1 icon me-3 d-flex">
                                                    <i class="fa-solid fa-arrow-right-from-bracket"></i>
                                                </div>
                                                <div class="w-100 d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <p class="p-0 dropdown-header text-dark">Logout</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>

                                    </li>
                                </ul>
                            </div>
                            <!-- responsive -->
                            <div class="row d-lg-none">
                                <div class="col-md-12">
                                    <router-link
                                        class="py-2 ps-3 border-radius-md"
                                        :to="{ name: 'home' }"
                                    >
                                        <div class="d-flex">
                                            <div class="h-10 mt-1 icon me-3 d-flex">
                                                <i
                                                    class="ni ni-single-copy-04 text-gradient text-success"
                                                ></i>
                                            </div>
                                            <div
                                                class="w-100 d-flex align-items-center justify-content-between"
                                            >
                                                <div>
                                                    <p class="p-0 dropdown-header text-dark">Profile</p>
                                                </div>
                                            </div>
                                        </div>
                                    </router-link>
                                    <router-link
                                        class="py-2 ps-3 border-radius-md"
                                        :to="{ name: 'home' }"
                                    >
                                        <div class="d-flex">
                                            <div class="h-10 mt-1 icon me-3 d-flex">
                                                <i class="ni ni-laptop text-gradient text-success"></i>
                                            </div>
                                            <div
                                                class="w-100 d-flex align-items-center justify-content-between"
                                            >
                                                <div>
                                                    <p class="p-0 dropdown-header text-dark">Help Center</p>
                                                </div>
                                            </div>
                                        </div>
                                    </router-link>
                                    <router-link
                                        class="py-2 ps-3 border-radius-md"
                                        :to="{ name: 'home' }"
                                    >
                                        <div class="d-flex">
                                            <div class="h-10 mt-1 icon me-3 d-flex">
                                                <i class="ni ni-badge text-gradient text-success"></i>
                                            </div>
                                            <div
                                                class="w-100 d-flex align-items-center justify-content-between"
                                            >
                                                <div>
                                                    <p class="p-0 dropdown-header text-dark">
                                                        Setting
                                                    </p>
                                                </div>
                                            </div>
                                        </div>
                                    </router-link>
                                    <router-link
                                        class="py-2 ps-3 border-radius-md"
                                        :to="{ name: 'home' }"
                                    >
                                        <a class="py-2 ps-3 border-radius-md" href="/auth/logout">
                                            <div class="d-flex">
                                                <div class="h-10 mt-1 icon me-3 d-flex">
                                                    <i class="ni ni-notification-70 text-gradient text-success"></i>
                                                </div>
                                                <div class="w-100 d-flex align-items-center justify-content-between">
                                                    <div>
                                                        <p class="p-0 dropdown-header text-dark">Logout</p>
                                                    </div>
                                                </div>
                                            </div>
                                        </a>

                                    </router-link>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End Navbar -->
</template>

<script>
export default {
    name: "Navbar",
    props: {
        btnBackground: {
            type: String,
            default: "",
        },
        isBlur: {
            type: String,
            default: "",
        },
        darkMode: {
            type: Boolean,
            default: false,
        },
    },
    data() {
        return {
            user: null,
        };
    },
    beforeMount() {
        this.user = JSON.parse(localStorage.getItem("user"));
    }
};
</script>
