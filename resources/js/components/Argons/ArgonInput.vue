<template>
    <div class="form-group">
        <div :class="hasIcon(icon)">
      <span v-if="iconDir === 'left'" class="input-group-text">
        <i :class="getIcon(icon)"></i>
      </span>
            <input
                :id="id"
                :type="type"
                class="form-control"
                :class="getClasses(size, success, error)"
                :name="name"
                :value="value"
                :placeholder="placeholder"
                :required="required"
                :disabled="disabled"
            />
            <span v-if="iconDir === 'right'" class="input-group-text">
        <i :class="getIcon(icon)"></i>
      </span>
        </div>
    </div>
</template>

<script>
export default {
    name: "ArgonInput",
    props: {
        size: {
            type: String,
            default: "default",
        },
        success: {
            type: Boolean,
            default: false,
        },
        error: {
            type: Boolean,
            default: false,
        },
        icon: {
            type: String,
            default: "",
        },
        iconDir: {
            type: String,
            default: "",
        },
        name: {
            type: String,
            default: "",
        },
        id: {
            type: String,
            default: "",
        },
        value: {
            type: String,
            default: "",
        },
        placeholder: {
            type: String,
            default: "",
        },
        type: {
            type: String,
            default: "text",
        },
        required: {
            type: Boolean,
            default: false,
        },
        disabled: {
            type: Boolean,
            default: false,
        },
    },
    methods: {
        getClasses: (size, success, error) => {
            let sizeValue, isValidValue;

            sizeValue = size ? `form-control-${size}` : null;

            if (error) {
                isValidValue = "is-invalid";
            } else if (success) {
                isValidValue = "is-valid";
            } else {
                isValidValue = "";
            }

            return `${sizeValue} ${isValidValue}`;
        },
        getIcon: (icon) => (icon ? icon : null),
        hasIcon: (icon) => (icon ? "input-group" : null),
    },
};
</script>
