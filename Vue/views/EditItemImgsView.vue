<script setup>
import { reactive, onMounted, ref } from "vue"
import { useRoute } from "vue-router"
import axios from "axios"
import BeatLoader from "vue-spinner/src/BeatLoader.vue"
import { useToast } from "vue-toastification"

// props
const props = defineProps({
    apiURL: String
})

// toast
const toast = useToast()

// item id
const route = useRoute()
const itemID = route.params.itemID

// state
const imgs = reactive({
    data: [],
    loading: false,
    error: false
})
const altTag = ref('')
const caption = ref('')
const btnClass = ref('btn_locked')
const formLoading = ref(false)

// get images
function getImgs() {
    imgs.loading = true
    axios.post(props.apiURL, {func: "Get Images", itemID: itemID})
        .then(results => processImages({type: "returned", data: results.data}))
        .catch(err => processImages({type: "error", data: err}))
}

function processImages({type, data}) {
    if (type === "returned") {
        imgs.data = data
    } else {
        imgs.error = true
        toast.error("Item images not found")
        if (data.err) {
            console.log(data.err)
        }
    }

    imgs.loading = false
}

onMounted(async () => {
    getImgs()
})

const uplFile = ref(null)
function imgToUpload(event) {
    uplFile.value = event.target.files[0]
    btnClass.value = ""
}

// upload image
function imgSend() {
    formLoading.value = true
    imgs.loading = true
    const formData = new FormData()
    formData.append("image", uplFile.value, uplFile.value.name)
    formData.append("func", "Upload Image")
    formData.append("itemID", itemID)
    formData.append("altTag", altTag)
    formData.append("caption", caption)
    axios.post(props.apiURL, formData)
    .then(result => processSend({type: "returned", data: result.data}))
    .catch(err => processSend({type: "error", data: err}))
}

function processSend({type, data}) {
    if (type === "returned" && data === "uploaded") {
        uplFile.value = null
        altTag.value = ""
        caption.value = ""
        btnClass.value = "btn_locked"
        getImgs()
        toast.success("Image uploaded")

    } else if (type === "returned" && data === "error size") {
        toast.error("Image size is too large")
    } else if (type === "returned" && data === "error size") {
        toast.error("Image type must be .jpg, .gif, .png, or .webp")
    } else {
        toast.error("Image could not be uploaded")
        if (data.err) {
            console.log(data.err)
        }
    }

    formLoading.value = false
    imgs.loading = false
}

// delete image
function handleDeleteImg(imgID, imgName) {
    if (confirm("Are you sure you want to delete this image?")){
        axios.post(props.apiURL, {func: "Delete Image", imgID: imgID, imgName: imgName})
            .then(result => processDeleteImg({type: "returned", data: result.data}))
            .catch(err => processDeleteImg({type: "error", data: err.data}))
    }
}

function processDeleteImg({type, data}) {
    if (type === "returned" && data === "deleted") {
        getImgs()
        toast.success("Image deleted")
    } else {
        toast.error("Image could not be deleted")
        if (data.err) {
            console.log(data.err)
        }
    }
}

// set image to main
function handleMainImg(imgID, itemID) {
    axios.post(props.apiURL, {func: "Set Main Image", imgID: imgID, itemID: itemID})
        .then(result => processMainImg({type: "returned", data: result.data}))
        .catch(err => processMainImg({type: "error", data: err.data}))
}

function processMainImg({type, data}) {
    if (type === "returned" && data === "updated") {
        getImgs();
        toast.success("Image set as main item image")
    } else {
        toast.error("Image could not be set to main")
        if (data.err) {
            console.log(data.err)
        }
    }
}

</script>

<template>
    <div class="popup_bg">
        <div class="popup_pnl">
            <div class="popup_close"><RouterLink to="/">&#10005;</RouterLink></div>
            <div class="popup_ttl">Mange item images</div>
            <div class="popup_pad popup_pnl_v3">
                <div class="popup_form">
                    <div v-if="formLoading" class="loading">
                        <BeatLoader color="#1E5D80" />
                    </div>
                    <p>
                        <label for="file">Upload image</label>
                        <span><input type="file" id="file" ref="file" accept=".jpg,.jpeg,.png,.gif,.webp" @change="imgToUpload"/></span>
                    </p>
                    <p>
                        <label for="altTag">Alt tag</label>
                        <span><input type="text" name="altTag" id="altTag" v-model="altTag" value=""></span>
                    </p>
                    <p>
                        <label for="caption">Caption</label>
                        <span><input type="text" name="caption" id="caption" v-model="caption" value=""></span>
                    </p>
                    <p :class="btnClass"><button class="btn_blue" @click="imgSend" >Upload</button></p>
                </div>

                <div class="popup_gallery popup_list">
                    <div v-if="imgs.error" class="error">
                        <span>Error loading images</span>
                    </div>
                    <div v-if="imgs.loading" class="loading">
                        <BeatLoader color="#1E5D80" />
                    </div>
                    <ul v-else>
                        <li v-for="img in imgs.data" :key="img">
                            <span v-if="img.isMain" class="main_item_img">
                                <span>&#10003</span>
                                <img :src="`https://www.greganderson.co.uk/shop/itemImages/thumb_${img.imgName}`" :alt="img.altTag">
                            </span>
                            <span v-else>
                                <img :src="`https://www.greganderson.co.uk/shop/itemImages/thumb_${img.imgName}`" :alt="img.altTag">
                            </span>
                            <span class="column_li_opts">
                                <span v-if="img.isMain" class="grey_disabled">Main image</span>
                                <span v-else>
                                    <a @click.prevent="handleMainImg(img.imgID, itemID)"class="nav_grey" href="">Set to main</a>
                                </span>
                                <a @click.prevent="handleDeleteImg(img.imgID, img.imgName)" class="nav_red">Delete</a>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</template>
