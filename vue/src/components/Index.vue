<script setup lang="ts">
import { useRoute, useRouter } from 'vue-router';
import PageHeader from './PageHeader.vue'
import { ref } from 'vue'
import { bootstrapTableTranslateFields, makeRequest } from '../helpers'
import { toast } from 'vue3-toastify';

const route = useRoute()
const router = useRouter()

const register = route.params.register[0]


const fields = ref([]);
const data = ref<Array<{ [key: string]: string }>>([]);

const fetchData = async () => {
    const response = await (await fetch(makeRequest(route.path.substring(1)))).json();
    fields.value = response.fields;
    data.value = response.data;
    console.log(data.value, fields.value)
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
    <div id="wrap-include-button">
        <b-button type="submit" variant="primary" @click="router.push({ path: 'form-' + register })">
            Incluir
        </b-button>
    </div>
    <Suspense>
        <template #default>
            <div id="wrap-table">
                <b-table @row-clicked="redirect" sticky-header small borderless striped hover :items="data"
                    :fields="[...bootstrapTableTranslateFields(fields), { key: 'Deletar', name: 'delete' }]">
                    <template #cell(Deletar)="name">
                        <button class="button material-symbols-outlined" size="sm" type="button"
                            @click="deleteRegister(name.index)">
                            delete
                        </button>
                    </template></b-table>
            </div>
        </template>
    </Suspense>
</template>

<style scoped>
table {
    width: 80%;
    margin: auto;
}

#wrap-include-button {
    display: flex;
    justify-content: center;
    width: 100%;
    margin-bottom: 15px;
    border-bottom: 1px solid rgba(0, 0, 0, 0.068);
    padding-bottom: 15px;
}
</style>
