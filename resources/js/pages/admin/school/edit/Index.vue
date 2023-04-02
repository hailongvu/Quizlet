<template>
    <div class="container-fluid">
        <div class="row mb-5">
            <div class="col-lg-2">
                <Menu />
            </div>
            <div class="col-lg-10 mt-lg-0 mt-4">
                <Content/>
            </div>
        </div>
    </div>
</template>

<script>
import Menu from "./Menu.vue";
import Content from "./Content.vue";

export default {
    name: "Edit",
    components: {
        Menu,
        Content,
    },
}
</script>

<style scoped>

</style>
