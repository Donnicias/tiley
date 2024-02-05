<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';

import CollageLayoutOne from "@/Components/PhotoCollageLayouts/CollageLayoutOne.vue";
import CollageLayoutTwo from "@/Components/PhotoCollageLayouts/CollageLayoutTwo.vue";
import CollageLayoutThree from "@/Components/PhotoCollageLayouts/CollageLayoutThree.vue";
import {ref} from "vue";
defineProps({
    layouts:Object
});

const active_layout = ref(1);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Photo Collage
                <div class="btn-group float-end" role="group" aria-label="Basic example">
                    <button type="button" :class="(active_layout===1?'btn btn-primary':'btn btn-outline-primary')" @click="active_layout = 1">Layout 1</button>
                    <button type="button" :class="(active_layout===2?'btn btn-primary':'btn btn-outline-primary')" @click="active_layout = 2">Layout 2</button>
                    <button type="button" :class="(active_layout===3?'btn btn-primary':'btn btn-outline-primary')" @click="active_layout = 3">Layout 3</button>
                </div>
            </h2>
        </template>

        <div class="container p-4">
            <CollageLayoutOne v-if="active_layout === 1"/>
            <CollageLayoutTwo v-if="active_layout === 2"/>
            <CollageLayoutThree v-if="active_layout === 3"/>
        </div>
    </AuthenticatedLayout>
</template>
<style>

</style>
