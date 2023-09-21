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
        <button id="go-back-button" v-on:click="goBack">
            <span class="material-symbols-outlined">
                arrow_back
            </span>
        </button>
        <h1>
            {{ fixHeader(title) }}
        </h1>
    </header>
</template>

<style  lang="scss" scoped>
header {
    display: flex;
    align-items: center;
    gap: 5px;
    width: 100%;
    height: calc(1.2em + 10px);
    margin: 0 0 1em 0;
    border-radius: 12px;
    padding: 5px 12px;
    background-color: var(--palete-color4);

    @media screen and (max-width: 425px) {
        margin-top: 55px;
    }

    display: flex;
    align-items: center;

    button {
        background: none;
        border: none;
        height: calc(1.2em + 10px);

        span {
            height: 100%;
        }
    }

    h1 {
        font-size: 1.2em;
        margin: 0;
    }
}

#go-back-button {
    cursor: pointer;
}
</style>
