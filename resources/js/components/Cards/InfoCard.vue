<template>
  <div class="card">
    <div class="card-header pb-0">
      <div class="d-flex">
        <h2>{{ title }}</h2>
      </div>
    </div>
    <div class="card-body pt-0">
      <p class="mb-0">{{ description }}</p>
    </div>
  </div>
</template>

<script>
import ArgonBadge from "@/components/Argons/ArgonBadge.vue";
import { mapState } from "vuex";

export default {
  name: "InfoCard",
  components: {
    ArgonBadge,
  },
  props: {
    title: {
      type: String,
      default: "",
    },
    description: {
      type: String,
      default: "",
    },
    badge: {
      type: Object,
      text: String,
      color: String,
      default: () => ({
        color: "success",
        text: "Moderate",
      }),
    },
  },
  computed: {
    ...mapState(["isRTL"]),
  },
};
</script>
