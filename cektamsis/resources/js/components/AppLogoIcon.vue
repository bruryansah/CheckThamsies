<script setup lang="ts">
import type { HTMLAttributes } from 'vue';

defineOptions({
  inheritAttrs: false,
});

interface Props {
  className?: HTMLAttributes['class'];
}

defineProps<Props>();
</script>

<template>
  <img
    src="https://smktamansiswa2jakarta.sch.id/wp-content/uploads/2022/03/LOGO-TAMSIS-MATERIAL-DESIGN.png"
    :class="className"
    v-bind="$attrs"
    alt="Logo Tamsis"
  />
</template>
