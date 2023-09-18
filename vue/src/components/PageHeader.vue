<script setup lang="ts">
import { useRouter } from 'vue-router';
import { fixHeader } from '../helpers';


defineProps<{ title: string }>()

const router = useRouter()

function goBack() {
    router.go(-1)
}

</script>

<template>
    <header class="right-shadow">
        <d-button id="go-back-button" v-on:click="goBack">
            <span class="material-symbols-outlined">
                arrow_back
            </span>
        </d-button>
        <h1>
            {{ fixHeader(title) }}
        </h1>
    </header>
</template>

<style  lang="scss" scoped>
header {
    display: flex;
    align-items: center;
    gap: 25px;
    width: 100%;
    height: calc(4em + 10px);
    margin: 0 0 1em 0;
    border-radius: 12px;
    padding: 5px 12px;
    background-color: var(--palete-color4);

    @media screen and (max-width: 375px) {
        margin-top: 35px;
    }
}

#go-back-button {
    cursor: pointer;
}
</style>
