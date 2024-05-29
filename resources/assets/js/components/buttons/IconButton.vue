<!-- IconButton.vue -->

<template>
    <button :class="buttonClass" @click="handleClick" type="button">
      <i :class="iconClass"></i>
      <span v-if="label">{{ label }}</span>
    </button>
  </template>
  
  <script>
  export default {
    props: {
      icon: {
        type: String,
        required: true,
      },
      label: {
        type: String,
        default: "",
      },
      size: {
        type: String,
        default: "medium",
        validator: (value) => ["small", "medium", "large"].includes(value),
      },
      color: {
        type: String,
        default: "default",
      },
    },
    computed: {
      buttonClass() {
        return `icon-button btn btn-${this.color} ${this.size}`;
      },
      iconClass() {
        return `${this.icon}`;
      },
    },
    methods: {
      handleClick() {
        this.$emit("click");
      },
    },
  };
  </script>
  
  <style scoped>
  .icon-button {
    cursor: pointer;
    border-radius: 4px;
    transition: background-color 0.3s;
  }
  
  .icon-button.small {
    font-size: 14px;
  }
  
  .icon-button.medium {
    font-size: 16px;
  }
  
  .icon-button.large {
    font-size: 18px;
  }

</style>
  