<script setup>
import { ref } from "vue"
import router from "@/router"
import axios from "axios"
import BeatLoader from "vue-spinner/src/BeatLoader.vue"
import { useToast } from "vue-toastification"

const props = defineProps({
    apiURL: String
})

// toast
const toast = useToast()

// inputs
const adminName = ref('')
const adminNameClass = ref('')
const adminEmail = ref('')
const adminEmailClass = ref('')
const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
const adminPwd = ref('')
const adminPwdClass = ref('')
const pwdRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@.#$!%*?&])[A-Za-z\d@.#$!%*?&]{8,15}$/;
const btnClass = ref('btn_locked')
const formLoading = ref(false)


// admin name validate
function handleAdminName() {
  if (adminName.value.length > 0 && (adminName.value.length < 6 || adminName.value.indexOf(' ') < 0)) {
    adminNameClass.value = "ipt_hint"
  } else {
    adminNameClass.value = ""
  }
}

// admin email validate
function handleAdminEmail() {
  if (adminEmail.value.length > 0 && !emailRegex.test(adminEmail.value)) {
    adminEmailClass.value = "ipt_hint"
  } else {
    adminEmailClass.value = ""
  }
}

// admin pwd validate
function handleAdminPwd() {
  if (adminPwd.value.length > 0 && !pwdRegex.test(adminPwd.value)) {
    adminPwdClass.value = "ipt_hint"
  } else {
    adminPwdClass.value = ""
  }
}

// form lock
function handleFormLock() {
  if (adminName.value.length >= 6 && adminName.value.indexOf(' ') >= 0 && emailRegex.test(adminEmail.value) && pwdRegex.test(adminPwd.value)) {
    btnClass.value = ""
  } else {
    btnClass.value = "btn_locked"
  }
}

// add admin to API
function handleSubmit() {
  formLoading.value = true;

  axios.post(props.apiURL, {func: "Add Admin", adminName: adminName.value, adminEmail: adminEmail.value, adminPwd: adminPwd.value})
    .then(result => processSubmit({type: "returned", data: result.data}))
    .catch(err => processSubmit({type: "error", data: err}))
}

// process API results
function processSubmit({type, data}) {
  if (type === "returned" && data === "added") {
    toast.success("Admin account added")
    router.push("/admin/")
          
  } else if (type === "returned" && data === "error name") {
    toast.error("Admin name must be minimum 2 words and 5 characters")
  } else if (type === "returned" && data === "error email") {
    toast.error("Admin email address must use the name@domain.com format")
  } else if (type === "returned" && data === "error pwd") {
    toast.error("Admin password must contain 8 or more lowercase, UPPERCASE, numbers & special characters") 
  } else if (type === "returned" && data === "error exist") {
    toast.error("Email address is already registered")
  } else {
    toast.error("Admin account could not be added")
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
      <div class="popup_ttl">Add admin account</div>
      <div class="popup_pad popup_pnl_v2">
        <div v-if="formLoading" class="loading">
          <BeatLoader color="#65A0C1"/>
        </div>
        <div class="popup_form">
          <form @submit.prevent="handleSubmit()">
            <p>
              <label for="adminName">Admin name</label>
              <span :class="adminNameClass">
                <input type="text" name="adminName" id="adminName" value="" v-model="adminName" @keyup="handleAdminName();handleFormLock()">
                <span class="ipt_hint_msg">Minimum 5 characters, 2 words</span>
              </span>
            </p>
            <p>
              <label for="adminEmail">Email (username)</label>
              <span :class="adminEmailClass">
                <input type="text" name="adminEmail" id="adminEmail" value="" v-model="adminEmail" @keyup="handleAdminEmail();handleFormLock()">
                <span class="ipt_hint_msg">name@domain.com</span>
              </span>
            </p>
            <p>
              <label for="adminPwd">Password</label>
              <span :class="adminPwdClass">
                <input type="text" name="adminPwd" id="adminPwd" value="" v-model="adminPwd" @keyup="handleAdminPwd();handleFormLock()">
                <span class="ipt_hint_msg">Min 8 lowercase, UPPERCASE, numbers & special characters</span>
              </span>
            </p>
            <p :class="btnClass"><button class="btn_blue">Submit</button></p>
          </form>
        </div>
      </div>
    </div>
  </div>  
</template>
