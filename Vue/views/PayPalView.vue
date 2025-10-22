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
const payPalEmail = ref('')
const payPalEmailClass = ref('')
const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
const btnClass = ref('btn_locked')
const formLoading = ref(false)
const formError = ref(false)

// get paypal data
function getPayPalData() {
    formLoading.value = true
    axios.post(props.apiURL, {func: "Get PayPal"})
        .then(results => processPayPalData({type: "returned", data: results.data}))
        .catch(err => processPayPalData({type: "error", data: err}))
}

function processPayPalData({type, data}) {
    if (type === "returned" && Object.keys(data).length > 0) {
        payPalEmail.value = data.payPalEmail
    } else {
        formError.value = true
        toast.error("PayPal email address not found")
        if (data.err) {
            console.log(data.err)
        }
    }

    formLoading.value = false
}

onMounted(async () => {
    getPayPalData()
})

// PayPal email validate
function handlePayPalEmail() {
  if (payPalEmail.value.length > 0 && !emailRegex.test(payPalEmail.value)) {
    payPalEmailClass.value = "ipt_hint"
    btnClass.value = "btn_locked"
  } else {
    payPalEmailClass.value = ""
    btnClass.value = ""
  }
}

// update PayPal email
function handleUpdate() {
    formLoading.value = true

    axios.post(props.apiURL, {func: "Update PayPal", payPalEmail: payPalEmail.value})
        .then(result => processUpdate({type: "returned", data: result.data}))
        .catch(err => processUpdate({type: "error", data: err}))
}

function processUpdate({type, data}) {
    if (type === "returned" && data === "updated") {
        toast.success("PayPal email address updated")
        router.push("/");
          
    } else if (type === "returned" && data === "error email") {
        toast.error("PayPal email address must use the name@domain.com format")
    } else {
        toast.error("PayPal email address could not be updated")
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
            <div class="popup_ttl">Manage PayPal email</div>
            <div :class="[formError ? 'popup_pad popup_pnl_v1 popup_error' : 'popup_pad popup_pnl_v1']">
                <div v-if="formLoading" class="loading">
                    <BeatLoader color="#65A0C1"/>
                </div>
                <div v-else class="popup_form">
                    <form @submit.prevent="handleUpdate()">
                        <p>
                            <label for="payPalEmail">PayPal email</label>
                            <span :class="payPalEmailClass">
                                <input type="text" name="payPalEmail" id="payPalEmail" value="" v-model="payPalEmail" @keyup="handlePayPalEmail()">
                                <span class="ipt_hint_msg">name@domain.com</span>
                            </span>
                        </p>
                        <p :class="btnClass"><button class="btn_blue">Update</button></p>
                    </form>
                </div>
            </div>
        </div>
    </div>  
</template>
