<script setup lang="ts">
import { useRoute } from 'vue-router';


// defineProps<>()

const route = useRoute()

const register = route.params.register

</script>

<template>
    <h1>
        Index {{ register }}
    </h1>
</template>

<style scoped></style>
