<script setup lang="ts">

import { ref } from 'vue';
import type { MenuItem } from '../types';

defineProps<{ menuItens: Array<MenuItem> }>()

const menuOpened = ref("")

</script>

<template>
    <aside id="wrap-side-bar" class="right-shadow">
        <section class="wrap-side-bar-item" v-for="(menuItem) in menuItens" :key="menuItem.path">
            <router-link :to="menuItem.path" @mouseover="menuOpened = menuItem.path" @mouseleave="menuOpened = 'false'">
                <img :src="menuItem.icon" />
            </router-link>
            <span v-if="menuOpened === menuItem.path">
                <h4>
                    {{ menuItem.name }}
                </h4>
            </span>
        </section>
    </aside>
</template>

<style lang="scss" scoped>
#wrap-side-bar {
    min-height: 100vh;
    background-color: var(--palete-color2);
    display: flex;
    flex-direction: column;
    align-items: center;
    padding: 5px 0;
    width: calc(2em + 32px);
    position: relative;

    .wrap-side-bar-item {
        display: flex;
        position: relative;
        width: calc(2em + 32px);
        height: calc(1em + 32px);
        justify-content: center;
        z-index: 10;

        a {
            height: 100%;
            width: 100%;
            cursor: pointer;
            transition: all 0.15s;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 11;
            background-color: var(--palete-color2);

            &:hover {
                background-color: var(--palete-color1);
            }
        }

        span {
            position: absolute;
            left: calc(2em + 32px);
            height: 100%;
            text-align: center;
            width: auto;
            top: -1px;
            padding: calc((1em + 32px)/2) 1em;
            align-items: center;
            display: flex;
            background-color: var(--palete-color1);
            border: 1px solid var(--palete-color2);
            box-sizing: border-box;
            border-left: none;
            animation: slide-in 0.2s;
            z-index: 10;
            border-radius: 0 7px 7px 0;



            @keyframes slide-in {
                from {
                    left: -200px;
                }

                to {
                    left: calc(2em + 32px);
                }
            }
        }
    }
}
</style>
