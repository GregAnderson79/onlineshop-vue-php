<script setup>
import { ref, onMounted } from "vue"
import router from "@/router"
import axios from "axios"
import BeatLoader from "vue-spinner/src/BeatLoader.vue"
import { useToast } from "vue-toastification"

// props
const props = defineProps({
    apiURL: String
})

// toast
const toast = useToast()

// refs
const delPrice = ref(0)
const delPriceClass = ref('')
const priceRegex = /^\d+\.?\d{0,2}?$/;
const btnClass = ref('btn_locked')
const formLoading = ref(false)
const formError = ref(false)

// get delivery price data from API
function getDelPriceData() {
    formLoading.value = true
    axios.post(props.apiURL, {func: "Get Delivery Price"})
        .then(results => processDelPriceData({type: "returned", data: results.data}))
        .catch(err => processDelPriceData({type: "error", data: err}))
}

// process API results
function processDelPriceData({type, data}) {
    if (type === "returned" && Object.keys(data).length > 0) {
        delPrice.value = data.delPrice
    } else {
        formError.value = true
        toast.error("Delivery price could not be found")
    }

    formLoading.value = false
}

onMounted(async () => {
    getDelPriceData()
})

// Delivery price validate
function handleDelPrice() {
  if (delPrice.value.length > 0 && !priceRegex.test(delPrice.value)) {
    delPriceClass.value = "ipt_hint"
    btnClass.value = "btn_locked"
  } else {
    delPriceClass.value = ""
    btnClass.value = ""
  }
}

// update admin in API
function handleUpdate() {
    formLoading.value = true

    axios.post(props.apiURL, {func: "Update Delivery Price", delPrice: delPrice.value})
        .then(result => processUpdate({type: "returned", data: result.data}))
        .catch(err => processUpdate({type: "error", data: err}))
}

// process API results
function processUpdate({type, data}) {
    if (type === "returned" && data === "updated") {
        toast.success("Delivery price updated")
        router.push("/")
    } else if (type === "returned" && data === "error money") {      
        toast.error("Delivery price must be a number formatted to 2 decimal places (123.45)")
    } else {
        toast.error("Delivery price could not be updated")
        if (data.err) {
            console.log(data.err)
        }
    }

    formLoading.value = false
}

</script>

<template>
    <div class="popup_bg">
        <div class="popup_pnl">
            <div class="popup_close"><RouterLink to="/">&#10005;</RouterLink></div>
            <div class="popup_ttl">Manage delivery price</div>
            <div :class="[formError ? 'popup_pad popup_pnl_v1 popup_error' : 'popup_pad popup_pnl_v1']">
                <div v-if="formLoading" class="loading">
                    <BeatLoader color="#65A0C1"/>
                </div>
                <div v-else class="popup_form">
                    <form @submit.prevent="handleUpdate()">
                        <p>
                            <label for="delPrice">Delivery Price</label>
                            <span :class="delPriceClass">
                                <input type="text" name="delPrice" id="delPrice" value="" v-model="delPrice" @keyup="handleDelPrice()">
                                <span class="ipt_hint_msg">Number formatted to 2 decimal places (123.45)</span>
                            </span>
                        </p>
                        <p :class="btnClass"><button class="btn_blue">Update</button></p>
                    </form>
                </div>
            </div>
        </div>
    </div>  
</template>
