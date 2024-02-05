
<template>
    <div>
        <h2>Collage Layout 3: Has three photo slots.</h2>
        <div v-if="uploading" class="progress-dialog bg-primary text-white">
            Uploading... {{ uploadProgress }}%
        </div>
        <div class="row" style="height: 600px; width:100% !important; margin: auto; padding: 0 !important;">
            <div class="col-md-6 border-1 border-black p-0 m-0" style="height: 100%; ">
                <div class="btn-group float-end" role="group" aria-label="Basic mixed styles example">
                    <input v-if="!leftImage" type="file" ref="leftFileInput" @change="uploadImage('left')" class="btn btn-outline-primary" accept="image/*" />
                    <button v-if="leftImage" type="button" class="btn btn-danger" @click="leftImage=null">Delete</button>
                </div>
                <br/>
                <!-- Left Slot -->
                <VueDraggableResizable
                    v-if="leftImage"
                    :x="leftImage.x"
                    :y="leftImage.y"
                    :w="leftImage.width"
                    :h="leftImage.height"
                    :parent="true"
                    :onResize="onResizeLeft"
                    :onDrag="onDragLeft"
                    :bounds="{ left: 0, top: 0, right: slotWidth / 2, bottom: slotHeight }"
                >
                    <img :src="leftImage.uri" alt="Left Collage Image" class="collage-image" />
                </VueDraggableResizable>
            </div>
            <div class="col-md-6" style="height: 100%;">
                <div class="row" style="height: 100%;width: 100%;">
                    <div class="col-md-12 border-1 border-black p-0 m-0" style="height: 50%;">
                        <div class="btn-group float-end" role="group" aria-label="Basic mixed styles example">
                            <input v-if="!rightTopImage" type="file" ref="rightTopFileInput" @change="uploadImage('rightTop')" class="btn btn-outline-primary" accept="image/*" />
                            <button v-if="rightTopImage" type="button" class="btn btn-danger" @click="rightTopImage=null">Delete</button>
                        </div>
                        <br/>
                        <!-- Right Top Slot -->
                        <VueDraggableResizable
                            v-if="rightTopImage"
                            :x="rightTopImage.x"
                            :y="rightTopImage.y"
                            :w="rightTopImage.width"
                            :h="rightTopImage.height"
                            :onResize="onResizeRightTop"
                            :onDrag="onDragRightTop"
                            :parent="true"
                            :bounds="{ left: slotWidth / 2, top: 0, right: slotWidth, bottom: slotHeight / 2 }"
                            :isResizable="true"
                        >
                            <img :src="rightTopImage.uri" alt="Right Top Collage Image" class="collage-image" />
                        </VueDraggableResizable>
                    </div>
                    <div class="col-md-12 border-1 border-black p-0 m-0" style="height: 50%;padding-top: -50px !important;">
                        <div class="btn-group float-end" role="group" aria-label="Basic mixed styles example">
                            <input v-if="!rightBottomImage" type="file" ref="rightBottomFileInput" @change="uploadImage('rightBottom')" class="btn btn-outline-primary" accept="image/*" />
                            <button v-if="rightBottomImage" type="button" class="btn btn-danger" @click="rightBottomImage=null">Delete</button>
                        </div>
                        <br/>
                        <!-- Right Bottom Slot -->
                        <VueDraggableResizable
                            v-if="rightBottomImage"
                            :x="rightBottomImage.x"
                            :y="rightBottomImage.y"
                            :w="rightBottomImage.width"
                            :h="rightBottomImage.height"
                            :onResize="onResizeRightBottom"
                            :onDrag="onDragRightBottom"
                            :parent="true"
                            :bounds="{ left: slotWidth / 2, top: slotHeight / 2, right: slotWidth, bottom: slotHeight }"
                            :isResizable="true"
                        >
                            <img :src="rightBottomImage.uri" alt="Right Bottom Collage Image" class="collage-image" />

                        </VueDraggableResizable>
                    </div>
                </div>
            </div>
        </div>
        <!-- Progress Dialog -->
        <button @click="saveImages" class="btn btn-primary float-end mt-5 mb-5">Save</button>
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


const onResizeRightTop = (handle, x, y, width, height) => {
    rightTopImage.value.width = width;
    rightTopImage.value.height = height;
    // rightTopImage.value.x = x;
    // rightTopImage.value.y = y;
};

const onResizeRightBottom = (handle, x, y, width, height) => {
    rightBottomImage.value.width = width;
    rightBottomImage.value.height = height;
    // rightBottomImage.value.x = x;
    // rightBottomImage.value.y = y;
};

const onResizeLeft = (handle, x, y, width, height) => {
    leftImage.value.width = width;
    leftImage.value.height = height;
    // leftImage.value.x = x;
    // leftImage.value.y = y;
};


const onDragRightTop = (image,x, y) => {
    rightTopImage.value.x = x;
    rightTopImage.value.y = y;
};


const onDragRightBottom = (image,x, y) => {
    rightBottomImage.value.x = x;
    rightBottomImage.value.y = y;
};

const onDragLeft = (image,x, y) => {
    leftImage.value.x = x;
    leftImage.value.y = y;
};

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
                    width: 250, // Set default width and height
                    height: 250,
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
