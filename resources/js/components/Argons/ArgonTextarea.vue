<template>
    <div class="form-group">
    <textarea
        :id="id"
        class="form-control"
        :name="name"
        :rows="rows"
        :placeholder="placeholder"
    >{{ this.value }}</textarea>
    </div>
</template>

<script>
export default {
    name: "ArgonTextarea",
    props: {
        id: {
            type: String,
            required: true,
        },
        placeholder: {
            type: String,
            default: "",
        },
        rows: {
            type: Number,
            default: 5,
        },
        name: {
            type: String,
            default: "",
        },
        value: {
            type: String,
            default: "",
        },
    },
};
</script>
