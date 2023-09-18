<script setup lang="ts">
import SideBar from './components/SideBar.vue'

</script>

<template>
  <SideBar />
  <main>
    <router-view />
  </main>
</template>

<style lang="scss" scoped>
main {
  position: relative;
  margin: 1em 1.5em 1em calc(1.5em + (4em + 16px));
  width: 100%;

    @media screen and (max-width: 375px) {
        width: 100vw;
        margin: 0;
    }
}
</style>
