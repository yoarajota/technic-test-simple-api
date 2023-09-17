<script setup lang="ts">
// @ts-nocheck

import { ref } from 'vue';
import { useRoute } from 'vue-router';
import PageHeader from './PageHeader.vue'
import Files from './Files.vue'
import { HtmlHTMLAttributes } from 'vue';
import { makeRequest, bootstrapTableTranslateFields, fixHeader } from '../helpers';
import { toast } from 'vue3-toastify';
import router from '../router';

// defineProps<>()

type TypeForm = { [key: string]: string | Array<TypeForm> };

const route = useRoute()
const [register, id] = route.params.register;

const submiting = ref(false)
const forms = ref({})
const data = ref<TypeForm>({})

const fetchData = async () => {
    const response = await (await fetch(makeRequest(route.path.substring(1)), { parmas: { id: id } })).json();
    forms.value = response.forms;
    data.value = response.data;
};

fetchData();

function addLine(event: Event) {
    event.preventDefault();

    const field = event?.target as HtmlHTMLAttributes;
    const listName = field?.id;

    if (listName && data.value[listName]) {
        const first = { ...data.value[listName][0] };

        data.value[listName] = [
            ...data.value[listName],
            first
        ] as Array<TypeForm>;

        for (let field in data.value[listName][0] as TypeForm) {
            data.value[listName][0][field as keyof TypeForm] = null;
        }
    }
}

function delLine(key: number, listName: string) {
    data.value[listName] = data.value[listName].filter((i, k) => k !== key + 1)
}

async function onSubmit(event: Event) {
    event.preventDefault()
    submiting.value = true;
    const toSend = { ...data.value };
    for (let form of forms.value) {
        if (form.type === "list") {
            toSend[form.name] = toSend[form.name].filter((item, key) => key !== 0);
        }
    }

    await fetch(makeRequest(route.path.substring(1)), { method: "POST", body: JSON.stringify(toSend), headers: { "Content-Type": "application/json" } }).then(async (response) => {
        const body = await response.json();
        toast[body.status](body.message)
    });
    submiting.value = false;
}
</script>

<template>
    <PageHeader :title="id ? register + ' ' + id : register" />
    <Suspense>
        <template #default>
            <b-form id="form" @submit="onSubmit">
                <div v-for="(formPart) in     forms    ">
                    <span v-if="formPart.type === 'list'">
                        <b-form :id="formPart.name" class="inline-form" @submit="addLine">
                            <span class="list-title">
                                <h3>
                                    {{ fixHeader(formPart.name) }}
                                </h3>
                            </span>
                            <span class="list-fields">
                                <span class="w-25" v-for="(field, key) of formPart.fields">
                                    <b-form-select v-if="field.type === 'select'"
                                        v-model="data[formPart.name][0][field.name]" :options="field.options">
                                    </b-form-select>

                                    <b-form-input v-if="field.type !== 'select'" :placeholder="field.label"
                                        :id="`field-list-${field.name}-${key}`" v-model="data[formPart.name][0][field.name]"
                                        :type="field.type" required></b-form-input>
                                </span>
                                <button class="material-symbols-outlined button">
                                    add
                                </button>
                            </span>
                        </b-form>

                        <b-table sticky-header small striped hover class="list-items"
                            :items="data[formPart.name].filter((_, k) => k !== 0)"
                            :fields="[...bootstrapTableTranslateFields(formPart.fields), { key: 'Deletar', name: 'delete' }]">

                            <template #cell(Deletar)="name">
                                <button class="button material-symbols-outlined" size="sm" type="button"
                                    @click="delLine(name.index, formPart.name)">
                                    delete
                                </button>
                            </template>
                        </b-table>
                    </span>

                    <span v-if="formPart?.type === 'form'" class='grid-form'>
                        <b-form-group v-for="(field, key) of     formPart.fields    " :label="field.label"
                            :label-for="`field-${field.name}-${key}`" :description="field.description">

                            <b-form-select class="form-control" v-if="field.type === 'select'"
                                v-model="data[formPart.name][field.name]"
                                :options="[{ value: null, text: 'Selecione' }, ...field.options]">
                            </b-form-select>

                            <b-form-input v-if="field.type !== 'select'" :id="`field-${field.name}-${key}`"
                                v-model="data[formPart.name][field.name]" :type="field.type"
                                :placeholder="field.placeholder" required></b-form-input>
                        </b-form-group>
                    </span>

                    <span v-if="formPart?.type === 'files'" @add="receiveEmit">
                        <Files :canDownload="!!id" :model="data[formPart.name]" />
                    </span>
                </div>
                <span id="wrap-submit">
                    <b-button type="submit" variant="primary">
                        <div v-if="!submiting" class="button-submit m-0">
                            {{ id ? "Atualizar cadastro" : "Salvar" }}
                        </div>
                        <div class="button-submit">
                            <div v-if="submiting" class="spinner-border spinner-border-sm" role="status">
                            </div>
                        </div>
                    </b-button>
                </span>
            </b-form>
        </template>
    </Suspense>
</template>

<style scoped>
.grid-form {
    display: grid;
    grid-template-columns: repeat(2, 1fr);
    gap: 15px;
}

.inline-form {
    border-top: 1px solid rgba(0, 0, 0, 0.143);
    padding-top: 12px;
    margin-top: 25px;
    z-index: 2;
}

.list-fields {
    display: flex;
    gap: 15px;
    position: relative;
}

.list-title {
    width: 100%;
}

.list-items {
    margin-top: 20px;
    position: relative;
    z-index: 1;
}

#form {
    display: flex;
    flex-direction: column;
    gap: 15px;
    position: relative;
    height: calc(100% + 400px);
    width: 100%;
}

#wrap-submit {
    position: fixed;
    bottom: 10px;
    width: fill-avaliable;
    display: flex;
    justify-content: center;
    width: -webkit-fill-available;
}

.button-submit {
    width: 200px;
}

.displayNone {
    display: none !important;
}
</style>