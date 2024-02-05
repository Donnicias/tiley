<template>
    <div>
        <div v-if="uploading" class="progress-dialog bg-primary text-white">
            Uploading... {{ uploadProgress }}%
        </div>
        <div class="row" style="height: 800px; width:100% !important; margin: auto; padding: 0 !important;">
            <div class="col-md-6 border-2 border-primary" style="height: 100%;">
                <!-- Left Slot -->
                <VueDraggableResizable
                    style="border: 1px solid brown"
                    v-if="leftImage"
                    :x="leftImage.x"
                    :y="leftImage.y"
                    :w="leftImage.width"
                    :h="leftImage.height"
                    :parent="true"
                    :bounds="{ left: 0, top: 0, right: slotWidth / 2, bottom: slotHeight }"
                    :isResizable="true"
                >
                    <img :src="leftImage.uri" alt="Left Collage Image" class="collage-image" />
                </VueDraggableResizable>
                <!-- Upload Buttons -->
                <input type="file" ref="leftFileInput" @change="uploadImage('left')" accept="image/*" />
            </div>

            <div class="col-md-6 border-2 border-primary" style="height: 100%;">
                <!-- Right Slot -->
                <VueDraggableResizable
                    style="border: 1px solid yellow"
                    v-if="rightImage"
                    :x="rightImage.x"
                    :y="rightImage.y"
                    :w="rightImage.width"
                    :h="rightImage.height"
                    :parent="true"
                    :bounds="{ left: slotWidth / 2, top: 0, right: slotWidth, bottom: slotHeight }"
                    :isResizable="true">
                    <img :src="rightImage.uri" alt="Right Collage Image" class="collage-image" />
                </VueDraggableResizable>
                <input type="file" ref="rightFileInput" @change="uploadImage('right')" accept="image/*" />
            </div>
        </div>
        <!-- Save Button -->
        <button @click="saveImages">Save</button>
    </div>
</template>

<script setup>
import VueDraggableResizable from 'vue-draggable-resizable';
import {ref} from 'vue';

const leftFileInput = ref(null);
const rightFileInput = ref(null);
const leftImage = ref(null);
const rightImage = ref(null);
const uploading = ref(false);
const uploadProgress = ref(0);
const slotWidth = 800; // Set your slot dimensions
const slotHeight = 500;

const uploadImage = (position) => {
    const fileInput = position === 'left' ? leftFileInput.value : rightFileInput.value;
    const file = fileInput.files[0];

    if (file) {
        const formData = new FormData();
        formData.append('image', file);

        axios.post('/api/upload-image', formData, {
            headers: {
                'Content-Type': 'multipart/form-data',
            },
            onUploadProgress: (progressEvent) => {
                uploadProgress.value = Math.round((progressEvent.loaded / progressEvent.total) * 100);
            },
        })
            .then((response) => {
                const newImage = {
                    uri: response.data.imageUri,
                    x: 0,
                    y: 0,
                    width: 100, // Set default width and height
                    height: 100,
                };

                position === 'left' ? (leftImage.value = newImage) : (rightImage.value = newImage);
            })
            .catch((error) => {
                console.error('Image upload failed:', error);
            });
    }
};

const saveImages = () => {
    // Logic to save image details to the backend
    console.log('Saved image details:', {
        layoutTypeId: 2, // Example layout type id
        left: {
            uri: leftImage.value.uri,
            x: leftImage.value.x,
            y: leftImage.value.y,
            width: leftImage.value.width,
            height: leftImage.value.height,
        },
        right: {
            uri: rightImage.value.uri,
            x: rightImage.value.x,
            y: rightImage.value.y,
            width: rightImage.value.width,
            height: rightImage.value.height,
        },
    });
};
</script>

<style scoped>
@import "vue-draggable-resizable/style.css";


.collage-image {
    width: 100%;
    height: 100%;
    //object-fit: cover;
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
