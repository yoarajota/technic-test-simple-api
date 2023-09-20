<script setup lang="ts">

import type { MenuItem } from '../types'
import { fixHeader, makeRequest, translate } from '../helpers';
import { ref } from 'vue';
import { useRoute } from 'vue-router';

const menuItems = ref<Array<MenuItem>>([]);

const route = useRoute()

const fetchMenuItems = async () => {
    const response = await fetch(makeRequest("menu"));
    menuItems.value = (await response.json()).menu;
};

fetchMenuItems();
</script>

<template>
    <aside id="wrap-side-bar">
        <section :class="'wrap-side-bar-item ' + (route.path === `/` ? 'active' : 'inactive')">
            <router-link :to="`/`">
                <span class="material-symbols-outlined">
                    home
                </span>
                <h5>
                    {{ translate("Home") }}
                </h5>
            </router-link>
        </section>
        <Suspense>
            <template #default>
                <section v-for="(menuItem) of menuItems"
                    :class="'wrap-side-bar-item ' + (route.params?.register?.[0] === menuItem.name ? 'active' : 'inactive')">
                    <router-link :to="`/list-${menuItem.name}`">
                        <span class="material-symbols-outlined">
                            {{ menuItem.icon }}
                        </span>
                        <h5>
                            {{ fixHeader(menuItem.name) }}
                        </h5>
                    </router-link>
                </section>
            </template>
        </Suspense>
    </aside>
</template>

<style lang="scss" scoped>
#wrap-side-bar {
    min-height: 100vh;
    box-sizing: border-box;
    display: flex;
    flex-direction: column;
    align-items: center;
    width: calc(8em + 16px);
    position: fixed;
    border-radius: 8px;
    background-color: var(--palete-color4);
    z-index: 10;
    gap: 6px;
    padding: 3em 0;

    .wrap-side-bar-item {
        display: flex;
        position: relative;
        justify-content: center;
        transition: all 0.15s;
        width: 95%;
        padding: 0 5%;
        margin: 0 2.5%;
        height: 2em;
        border-radius: 12px;

        a {
            width: 100%;
            cursor: pointer;
            transition: all 0.2s;
            z-index: 10;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 7px;

            span {
                width: 20%;
                color: var(--palete-color3) !important;
            }

            h5 {
                width: 80%;
                margin: 0;
                font-size: 1rem;
                color: var(--palete-color3) !important;
            }

        }

        &.active {
            background-color: var(--palete-color5);

            a {
                h5 {
                    font-weight: 900;
                }

                transform: scale(1.02);
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

    @media screen and (max-width: 375px) {
        margin: 0;
        width: 100vw;
        height: 40px;
        min-height: 40px;
        top: 0;
    }
}
</style>
