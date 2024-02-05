<template>
    <div>
        <!-- Upload Button -->
        <input type="file" ref="fileInput" @change="uploadImage" accept="image/*" />

        <!-- Image Slot -->
        <VueDraggableResizable
            v-if="image"
            :x="image.x"
            :y="image.y"
            :w="image.width"
            :h="image.height"
            :parent="true"
            :bounds="{ left: 0, top: 0, right: slotWidth, bottom: slotHeight }"
            :isResizable="true"
        >
            <img :src="image.uri" alt="Collage Image" class="collage-image" />
        </VueDraggableResizable>

        <!-- Progress Dialog -->
        <div v-if="uploading" class="progress-dialog">
            Uploading... {{ uploadProgress }}%
        </div>

        <!-- Save Button -->
        <button @click="saveImage">Save</button>

        <div style="height: 500px; width: 500px; border: 1px solid red; position: relative;">
            <vue-draggable-resizable :w="100" :h="100" :parent="true" :isResizable="true">
                <p>Hello! I'm a flexible component. You can drag me around and you can resize me.</p>
            </vue-draggable-resizable>
        </div>
    </div>
</template>

<script setup>
import VueDraggableResizable from 'vue-draggable-resizable';
import { ref } from 'vue';

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
