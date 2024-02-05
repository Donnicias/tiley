
<template>
    <div>
        <div v-if="uploading" class="progress-dialog bg-primary text-white">
            Uploading... {{ uploadProgress }}%
        </div>
        <div class="row" style="height: 800px; width:100% !important; margin: auto; padding: 0 !important;">
            <div class="col-md-6 border-2 border-primary" style="height: 100%;">
                <!-- Left Slot -->
                <VueDraggableResizable
                    v-if="leftImage"
                    :x="leftImage.x"
                    :y="leftImage.y"
                    :w="leftImage.width"
                    :h="leftImage.height"
                    :parent="true"
                    @resizing="onResize(leftImage)"
                    @dragging="onDrag(leftImage)"
                    :bounds="{ left: 0, top: 0, right: slotWidth / 2, bottom: slotHeight }"
                >
                    <img :src="leftImage.uri" alt="Left Collage Image" class="collage-image" />
                </VueDraggableResizable>
                <input type="file" ref="leftFileInput" @change="uploadImage('left')" accept="image/*" />
            </div>
            <div class="col-md-6" style="height: 100%;">
                <div class="row" style="height: 100%;width: 100%;">
                    <div class="col-md-12 border-2 border-warning p-2" style="height: 50%;">
                        <!-- Right Top Slot -->
                        <VueDraggableResizable
                            v-if="rightTopImage"
                            :x="rightTopImage.x"
                            :y="rightTopImage.y"
                            :w="rightTopImage.width"
                            :h="rightTopImage.height"
                            @resizing="onResize"
                            @dragging="onDrag(rightTopImage)"
                            :parent="true"
                            :bounds="{ left: slotWidth / 2, top: 0, right: slotWidth, bottom: slotHeight / 2 }"
                            :isResizable="true"
                        >
                            <img :src="rightTopImage.uri" alt="Right Top Collage Image" class="collage-image" />
                        </VueDraggableResizable>
                        <input type="file" ref="rightTopFileInput" @change="uploadImage('rightTop')" accept="image/*" />
                    </div>
                    <div class="col-md-12 border-2 border-danger p-2" style="height: 50%;">
                        <!-- Right Bottom Slot -->
                        <VueDraggableResizable
                            v-if="rightBottomImage"
                            :x="rightBottomImage.x"
                            :y="rightBottomImage.y"
                            :w="rightBottomImage.width"
                            :h="rightBottomImage.height"
                            @resizing="onResize(rightBottomImage)"
                            @dragging="onDrag(rightBottomImage)"
                            :parent="true"
                            :bounds="{ left: slotWidth / 2, top: slotHeight / 2, right: slotWidth, bottom: slotHeight }"
                            :isResizable="true"
                        >
                            <img :src="rightBottomImage.uri" alt="Right Bottom Collage Image" class="collage-image" />

                        </VueDraggableResizable>
                        <input type="file" ref="rightBottomFileInput" @change="uploadImage('rightBottom')" accept="image/*" />
                    </div>
                </div>
            </div>
        </div>
        <!-- Progress Dialog -->
        <button @click="saveImages">Save</button>
    </div>
</template>

<script setup>
import VueDraggableResizable from 'vue-draggable-resizable';
import { ref } from 'vue';

const leftFileInput = ref(null);
const rightTopFileInput = ref(null);
const rightBottomFileInput = ref(null);
const leftImage = ref(null);
const rightTopImage = ref(null);
const rightBottomImage = ref(null);
const uploading = ref(false);
const uploadProgress = ref(0);
const slotWidth = window.innerWidth; // Set the slot width to the entire screen width
const slotHeight = 500; // Set your slot height

const onResize = (handle, x, y, width, height) =>{
    console.log('handle',handle);
    console.log('height',height);
    console.log('width',width);
    console.log('x',x);
    console.log('y',y);
    // console.log('image',image);
}

const onDrag = (event) => {
    console.log('event',event);
    // console.log('y',y);
    // console.log('image',image);
}
const uploadImage = (position) => {
    uploading.value = true;
    const fileInput =
        position === 'left'
            ? leftFileInput.value
            : position === 'rightTop'
                ? rightTopFileInput.value
                : rightBottomFileInput.value;
    const file = fileInput.files[0];

    if (file) {

        const formData = new FormData();
        formData.append('image', file);

        axios
            .post('/api/upload-image', formData, {
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

                position === 'left'
                    ? (leftImage.value = newImage)
                    : position === 'rightTop'
                        ? (rightTopImage.value = newImage)
                        : (rightBottomImage.value = newImage);
                uploading.value = false;
            })
            .catch((error) => {
                console.error('Image upload failed:', error);
                uploading.value = false;
            });
    }
};

const saveImages = () => {
    //Logic to save image details to the backend
    console.log('Saved image details:', {
        left: {
            uri: leftImage.value.uri,
            x: leftImage.value.x,
            y: leftImage.value.y,
            width: leftImage.value.width,
            height: leftImage.value.height,
        },
        rightTop: {
            uri: rightTopImage.value.uri,
            x: rightTopImage.value.x,
            y: rightTopImage.value.y,
            width: rightTopImage.value.width,
            height: rightTopImage.value.height,
        },
        rightBottom: {
            uri: rightBottomImage.value.uri,
            x: rightBottomImage.value.x,
            y: rightBottomImage.value.y,
            width: rightBottomImage.value.width,
            height: rightBottomImage.value.height,
        },
    });
};
</script>

<style scoped>
@import "vue-draggable-resizable/style.css";
.collage-container {
    display: flex;
    width: 100vw; /* Make the container 100% of the viewport width */
}

.collage-image {
    width: 100%;
    height: 100%;
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
