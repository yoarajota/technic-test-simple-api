<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import PageHeader from './PageHeader.vue'
import { ref } from 'vue'
import { bootstrapTableTranslateFields, makeRequest, findSelectColor } from '../helpers'
import { toast } from 'vue3-toastify';

const route = useRoute()
const router = useRouter()

const register = route.params.register[0]


const fields = ref<Array<{ [key: string]: string }>>([]);
const data = ref<Array<{ [key: string]: string }>>([]);

const fetchData = async () => {
    const response = await (await fetch(makeRequest(route.path.substring(1)))).json();
    fields.value = bootstrapTableTranslateFields(response.fields);
    data.value = response.data;
};

fetchData();

type Data = {
    id: number
}

function redirect(a: Data) {
    router.push({ path: `/form-${register}/${a?.id}` });
}

type DeleteResponse = { status: string, message: string }
async function deleteRegister(index: number) {
    await fetch(makeRequest("delete-" + register + "/" + data.value[index].id), { method: "DELETE" }).then(async (response) => {
        const resp: DeleteResponse = await response.json()
        // @ts-ignore
        toast[resp.status as keyof DeleteResponse](resp.message);

        if (response.status === 200) {
            data.value = data.value.filter((_, k) => k !== index);
        }
    });

}

</script>

<template>
    <PageHeader :title="register" />
    <div id="wrap-include-button" class="default-submit-button">
        <b-button type="submit" variant="primary" @click="router.push({ path: 'form-' + register })">
            Incluir
        </b-button>
    </div>
    <Suspense>
        <template #default>
            <div id="wrap-table" class="right-shadow">
                <b-table @row-clicked="redirect" sticky-header small borderless striped hover head-variant="dark" responsive
                    :items="data"
                    :fields="[...fields, { key: 'Deletar', label: '', name: 'delete' }]">
                    <template #cell(Deletar)="data">
                        <button class="button material-symbols-outlined" size="sm" type="button"
                            @click="deleteRegister(data.index)">
                            delete
                        </button>
                    </template>
                    <template #cell()="data">
                        <span :class="findSelectColor(data) + ' ' + 'td-' + data.field.type">
                            {{ data.value }}
                        </span>
                    </template>
                </b-table>
            </div>
        </template>
    </Suspense>
</template>

<style>
.table> :not(caption)>*>* {
    background: var(--palete-color4) !important;
}
</style>

<style lang="scss" scoped>
$enable-shadows: true;

table {
    width: 80%;
    margin: auto;
}

#wrap-include-button {
    display: flex;
    justify-content: center;
    width: 100%;
    padding: 15px 0 15px 0;
    align-items: center;
}

#wrap-table {
    padding: 10px 12px;
    border-radius: 12px;
    height: 55vh;
    background-color: var(--palete-color4);
}
</style>
