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
  margin: 3em 1.5em 1em calc(1.5em + (8em + 16px));
  width: 100%;

    @media screen and (max-width: 425px) {
        width: 100vw;
        margin: 0;
    }
}

nav {
  height: 3em;
  width: 100%;
  position: fixed;
}
</style>
