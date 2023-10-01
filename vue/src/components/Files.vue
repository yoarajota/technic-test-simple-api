<script setup lang="ts">
import { ref } from 'vue';
import { PropType } from 'vue';
import { makeRequest } from '../helpers';
import { useRoute } from 'vue-router';

const props = defineProps({
    model: {
        type: Array as PropType<any[]>,
        required: true,
    }
});

const route = useRoute()
const [register, id] = route.params.register;

const preview = ref(props.model);
const holdPath = ref<Array<number>>([]);

function addFolder() {
    var pointer = props.model;
    for (let index of holdPath.value) {
        pointer = pointer[index].relations;
    }
    pointer.push({
        type: 'folder',
    })
}

async function addFile(event: Event) {
    const field = event?.target as HTMLInputElement;

    let pointer = props.model;
    for (let index of holdPath.value) {
        pointer = pointer[index].relations;
    }

    if (field.files?.[0]) {
        let reader = new FileReader();
        reader.readAsDataURL(field.files[0]);
        reader.onload = function () {
            pointer.push({
                type: 'file',
                file: reader.result
            })
        }
    }
}

function del(indexToRemove: number) {
    let pointer = props.model;

    for (let pathIndex of holdPath.value) {
        pointer = pointer[pathIndex].relations;
    }

    pointer.splice(indexToRemove, 1);
}

function returnPreview() {
    holdPath.value.pop()
    let pointer = props.model;
    for (let index of holdPath.value) {
        pointer = pointer[index].relations;
    }
    preview.value = pointer;
}

function changePreview(key: number, folder: any) {
    holdPath.value.push(key)
    if (!folder.relations) {
        folder.relations = [];
    }
    preview.value = folder.relations
}

function handle(key: number, folder: any) {
    if (folder.type === "folder") {
        changePreview(key, folder);
    } else if (id) {
        download(folder.path);
    }
}

async function download(path: string) {
    fetch(makeRequest('download-files-' + register + `?path=${path}`)).then(async (blob) => {
        let file = window.URL.createObjectURL(await blob.blob());
        window.open(file);
    });
}

</script>

<template>
    <span id="title">
        <h3>
            Arquivos
        </h3>
    </span>
    <div class="wrap-files">
        <span v-if="holdPath.length > 0">
            <span class="material-symbols-outlined clickable" @click="returnPreview">
                keyboard_return
            </span>
        </span>
        <span v-for="(relation, key) of preview" class="file-wrap">
            <span class="material-symbols-outlined clickable" @click="handle(key, relation)">
                {{ relation.type === "file" ? "download" : "folder" }}
            </span>

            <span class="delete-file material-symbols-outlined" @click="del(key)">
                close
            </span>
        </span>
        <span class="file-wrap add">
            <span @click="addFolder" title="Adicionar uma pasta.">
                <span class="material-symbols-outlined clickable">
                    folder_copy
                </span>
            </span>
        </span>
        <span class="file-wrap add">
            <span>
                <label for="file" title="Adicionar um arquivo.">
                    <span class="material-symbols-outlined clickable">
                        upload_file
                    </span>
                </label>
                <input accept="image/jpeg, image/png, application/pdf, text/plain" id="file" type="file" ref="newFile"
                    @change="addFile" />
            </span>
        </span>
    </div>
</template>

<style  lang="scss" scoped>
.clickable {
    cursor: pointer;
    font-size: 2.2rem;
    margin: 5px;
}


.delete-file {
    top: 0;
    right: 0;
    position: absolute;
    font-size: 1.1rem;
    cursor: pointer;
}

.file-wrap {
    position: relative;
    border: 1px solid var(--palete-color3);
    border-radius: 6px;
}

.wrap-files {
    border: 1px solid var(--palete-color3);
    padding: 20px 15px;
    min-height: 300px;
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(75px, 1fr));
    gap: 0px 0px;
    grid-template-areas:
        ". . . . . . ."
        ". . . . . . .";
    max-width: 800px;
    margin: 0 auto;
    gap: 15px;
    justify-content: center;
    border-radius: 12px;
    margin-top: 25px;

    &>span {
        width: 75px;
        display: flex;
        align-items: center;
        justify-content: center;
        height: 75px;
    }
}

#file {
    display: none;
}

.add {
    color: var(--palete-color5);
}

#title {
    width: 100%;
    text-align: center;
}
</style>
