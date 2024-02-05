<template>
    <div>
        <div v-if="uploading" class="progress-dialog bg-primary text-white">
            Uploading... {{ uploadProgress }}%
        </div>
        <div class="row p-0 m-0" style="height: 500px; width:100% !important; margin: auto; padding: 0 !important;">
            <div class="col-md-6 border-1 border-black m-0 p-0" style="height: 100%;">
                <!-- Left Slot -->
                <div v-if="uploading" class="progress-dialog bg-primary text-white">
                    Uploading... {{ uploadProgress }}%
                </div>
                <div class="btn-group float-end" role="group" aria-label="Basic mixed styles example">
                    <input v-if="!leftImage" type="file" ref="leftFileInput" @change="uploadImage('left')" class="btn btn-outline-primary" accept="image/*" />
                    <button v-if="leftImage" type="button" class="btn btn-danger" @click="leftImage=null">Delete</button>
                </div>
                <br/>
                <VueDraggableResizable
                    style="border: 1px solid brown"
                    v-if="leftImage"
                    :x="leftImage.x"
                    :y="leftImage.y"
                    :w="leftImage.width"
                    :h="leftImage.height"
                    :parent="true"
                    @click="active_slot = 1"
                    :onResize="onResizeLeft"
                    :onDrag="onDragLeft"
                    :bounds="{ left: 0, top: 0, right: slotWidth / 2, bottom: slotHeight }"
                    :isResizable="true"
                >
                    <img :src="leftImage.uri" alt="Left Collage Image" class="collage-image" />
                </VueDraggableResizable>
                <!-- Upload Buttons -->
            </div>

            <div class="col-md-6 border-2 p-0 m-0 border-primary" style="height: 100%;">
                <div class="btn-group float-end" role="group" aria-label="Basic mixed styles example">
                    <input v-if="!rightImage" type="file" ref="rightFileInput" @change="uploadImage('right')" class="btn btn-outline-primary" accept="image/*" />
                    <button v-if="rightImage" type="button" class="btn btn-danger" @click="rightImage=null">Delete</button>
                </div>
                <br/>
                <!-- Right Slot -->
                <VueDraggableResizable
                    style="border: 1px solid yellow"
                    v-if="rightImage"
                    :x="rightImage.x"
                    :y="rightImage.y"
                    :w="rightImage.width"
                    :h="rightImage.height"
                    :parent="true"
                    :onResize="onResizeRight"
                    :onDrag="onDragRight"
                    :bounds="{ left: slotWidth / 2, top: 0, right: slotWidth, bottom: slotHeight }"
                    :isResizable="true">
                    <img :src="rightImage.uri" alt="Right Collage Image" class="collage-image" />
                </VueDraggableResizable>
            </div>
        </div>
        <!-- Save Button -->
        <button @click="saveImages" class="btn btn-primary float-end mt-5 mb-5">Save</button>
    </div>
</template>

<script setup>
import VueDraggableResizable from 'vue-draggable-resizable';
import {ref} from 'vue';

const image = ref(null);

const leftFileInput = ref(null);
const rightFileInput = ref(null);
const leftImage = ref(null);
const rightImage = ref(null);
const uploading = ref(false);
const uploadProgress = ref(0);
const slotWidth = 800; // Set your slot dimensions
const slotHeight = 500;

const active_slot = ref(null);

const uploadImage = (position) => {
    const fileInput = position === 'left' ? leftFileInput.value : rightFileInput.value;
    const file = fileInput.files[0];
    uploading.value = true;

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
                uploading.value = false;
            })
            .catch((error) => {
                console.error('Image upload failed:', error);
                uploading.value = false;
            });
    }

};


const onResizeRight = (handle, x, y, width, height) => {
    rightImage.value.width = width;
    rightImage.value.height = height;
    rightImage.value.x = x;
    rightImage.value.y = y;
};

const onResizeLeft = (handle, x, y, width, height) => {
    leftImage.value.width = width;
    leftImage.value.height = height;
    rightImage.value.x = x;
    rightImage.value.y = y;
};


const onDragRight = (image,x, y) => {
    rightImage.value.x = x;
    rightImage.value.y = y;
};

const onDragLeft = (image,x, y) => {
    leftImage.value.x = x;
    leftImage.value.y = y;
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
