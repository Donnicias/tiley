<template>
    <div>
        <div v-if="uploading" class="progress-dialog bg-primary text-white">
            Uploading... {{ uploadProgress }}%
        </div>
        <div class="btn-group float-end" role="group" aria-label="Basic mixed styles example">
            <input v-if="!image" type="file" ref="fileInput" @change="uploadImage" class="btn btn-outline-primary" accept="image/*" />
            <button v-if="image" type="button" class="btn btn-danger" @click="image=null">Delete</button>
            <button v-if="image" type="button" class="btn btn-warning" @click="saveImage">Save</button>
        </div>
        <br/>
        <div class="row p-0 m-0" style="height: 800px; width:100% !important; margin: auto; padding: 0 !important;">
            <div class="col-md-12 border-1 border-black p-0 m-0" style="height: 100%;">
                <!-- Image Slot -->
                <VueDraggableResizable
                    v-if="image"
                    :x="image.x"
                    :y="image.y"
                    :w="image.width"
                    :h="image.height"
                    :onResize="onResize"
                    :onDrag="onDrag"
                    :parent="true"
                    :active="true"
                    :bounds="{ left: 0, top: 0, right: slotWidth, bottom: slotHeight }"
                    :isResizable="true"
                >
                    <img :src="image.uri" alt="Collage Image" class="collage-image" />
                </VueDraggableResizable>
            </div>
        </div>
    </div>
</template>

<script setup>
import VueDraggableResizable from 'vue-draggable-resizable';
import { ref } from 'vue';

const onResize = (handle, x, y, width, height) => {
    image.value.width = width;
    image.value.height = height;
    image.value.x = x;
    image.value.y = y;
};


const onDrag = (x, y) => {
    image.value.x = x;
    image.value.y = y;
};

const fileInput = ref(null);
const image = ref(null);
const uploading = ref(false);
const uploadProgress = ref(0);
const slotWidth = 500; // Set your slot dimensions
const slotHeight = 500;

const uploadImage = () => {
    const file = fileInput.value.files[0];

    if (file) {
        const formData = new FormData();
        formData.append('image', file);

        // You can adjust the API endpoint accordingly
        axios.post('/api/upload-image', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            onUploadProgress: (progressEvent) => {
                uploadProgress.value = Math.round((progressEvent.loaded / progressEvent.total) * 100);
            },
        })
            .then((response) => {
                image.value = {
                    uri: response.data.imageUri,
                    x: 0,
                    y: 0,
                    width: 100, // Set default width and height
                    height: 100,
                };
            })
            .catch((error) => {
                console.error('Image upload failed:', error);
            });
    }
};

const saveImage = () => {
    // Logic to save image details (layout type id, x, y, width, height) to the backend
    console.log('Saved image details:', {
        layoutTypeId: 1, // Example layout type id
        uri: image.value.uri,
        x: image.value.x,
        y: image.value.y,
        width: image.value.width,
        height: image.value.height,
    });
};
</script>

<style scoped>
@import "vue-draggable-resizable/style.css";
.collage-image {
    width: 100%;
    height: 100%;
    object-fit: cover;
}

.progress-dialog {
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background-color: #0007fd;
    padding: 20px;
    border: 1px solid #ef0e0e;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}
</style>
