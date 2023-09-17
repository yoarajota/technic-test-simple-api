<script setup lang="ts">

import type { MenuItem } from '../types'
import { fixHeader, makeRequest } from '../helpers';
import { ref } from 'vue';

const menuItems = ref<Array<MenuItem>>([]);

const fetchMenuItems = async () => {
    const response = await fetch(makeRequest("menu"));
    menuItems.value = await response.json();
};

fetchMenuItems();
</script>

<template>
    <aside id="wrap-side-bar" class="right-shadow">
        <Suspense>
            <template #default>
                <section class="wrap-side-bar-item" v-for="(menuItem) of menuItems">
                    <router-link :to="`/list-${menuItem.name}`">
                        <span class="material-symbols-outlined">
                            {{ menuItem.icon }}
                        </span>
                        <span>
                            <p>
                                    {{ fixHeader(menuItem.name) }}
                            </p>
                        </span>
                    </router-link>
                </section>
            </template>
        </Suspense>
    </aside>
</template>

<style lang="scss" scoped>
#wrap-side-bar {
    min-height: calc(100vh - 10px);
    border: 2px solid var(--palete-color2);
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: calc(4em + 16px);
    position: fixed;
    margin: 5px 0 0 5px;
    border-radius: 8px;
    background-color: var(--palete-color1);

    p {
        margin: 0;
        color: var(--palete-color5);
        font-family: var(--roboto);
        font-size: 0.75rem;
    }

    .wrap-side-bar-item {
        display: flex;
        position: relative;
        width: 100%;
        justify-content: center;
        height: calc(4em + 16px);
        animation: opacity 1.2s;

        a {
            width: 100%;
            cursor: pointer;
            transition: all 0.2s;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-direction: column;
            opacity: 0.8;

            &:hover {
                opacity: 1;
            }
        }

        @keyframes opacity {
            from {
                opacity: 0;
            }

            to {
                opacity: 0.8;
            }
        }
    }
}
</style>
