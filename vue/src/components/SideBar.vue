<script setup lang="ts">

import type { MenuItem } from '../types';

defineProps<{ menuItens: Array<MenuItem> }>()

</script>

<template>
    <div id="wrap-side-bar" class="right-shadow">
        <span v-for="(menuItem) in menuItens" :key="menuItem.path">
            {{ menuItem.name }}
        </span>
    </div>
</template>

<style scoped>
#wrap-side-bar {
    height: 100vh;
    position: absolute;
    background-color: var(--palete-color2);
    width: 110px;
}
</style>
