<script setup lang="ts">
import SideBar from './components/SideBar.vue'
import { MenuItem } from './types';

const menuItens: Array<MenuItem> = [{ name: "Clientes", icon: "/person.svg", path: "/temp-clientes" }];
</script>

<template>
  <SideBar :menuItens="menuItens" />
  <main>
    <router-view />
  </main>
</template>

<style scoped>
main {
  z-index: 1;
}
</style>
