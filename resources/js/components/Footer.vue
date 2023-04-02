<template>
    <footer class="py-3 footer">
        <div class="container-fluid">
            <div class="row align-items-center justify-content-lg-between">
                <div class="mb-4 col-lg-6 mb-lg-0">
                    <div class="text-sm text-center copyright text-muted text-lg-start">
                        ©
                        {{ new Date().getFullYear() }}, made with
                        <i class="fa fa-heart"></i> by
                        <span class="font-weight-bold">Let Learn Team</span>
                    </div>
                </div>
                <div class="col-lg-6">
                    <ul class="nav nav-footer justify-content-center justify-content-lg-end">
                        <li class="nav-item">
                            <a href="https://fb.me/ngotuananh2101" class="nav-link text-muted" target="_blank">Tuan
                                Anh</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </footer>
</template>

<script>
export default {
    name: "AppFooter",
};
</script>
