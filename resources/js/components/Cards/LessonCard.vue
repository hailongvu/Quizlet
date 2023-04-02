<template>
  <div class="mb-3 card">
    <div class="p-4 card-body">
      <div
        class="d-flex justify-content-between"
      >
        <div :class="classContent">
          <div class="numbers">
            <p
              class="mb-0 text-sm text-uppercase font-weight-bold mb-2"
            >
              {{ typeof title === "string" ? title : ""}}
            </p>
            <h5 :class="`mb-0 font-weight-bolder ${value}`" style="white-space: break-spaces;">
              {{
                value
              }}
            </h5>
            <!--  eslint-disable-next-line vue/no-v-html -->
            <p v-if="description" class="mt-2 mb-0"><span class='text-sm font-weight-bolder text-success' :style=" fontsize?'font-size: ' + fontsize + 'px !important;' : ''">{{ description }}</span></p>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "MiniStatisticsCard",
  props: {
    title: {
      type: String,
      required: true,
    },
    description: {
      type: String,
      default: "",
    },
    value: {
      type: String,
      required: true,
    },
    fontsize: {
      type: String,
      default: "",
    },
    classContent: {
      type: String,
      default: "",
    },
  },
};
</script>
