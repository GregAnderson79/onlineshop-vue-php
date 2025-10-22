<script setup>
import { ref } from "vue"
import axios from "axios"
import BeatLoader from "vue-spinner/src/BeatLoader.vue"
import { useToast } from "vue-toastification"

// props, emits
const emit = defineEmits(["emitLogin"])
const props = defineProps({
    apiURL: String
})

// toast
const toast = useToast()

// inputs
const loginEmail = ref('')
const loginEmailClass = ref('')
const emailRegex = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;
const loginPwd = ref('')
const loginPwdClass = ref('')
const pwdRegex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@.#$!%*?&])[A-Za-z\d@.#$!%*?&]{8,15}$/;
const btnClass = ref('btn_locked')
const formLoading = ref(false)
const formError = ref(false)

// admin login validate
function handleLoginEmail() {
  if (loginEmail.value.length > 0 && !emailRegex.test(loginEmail.value)) {
    loginEmailClass.value = "ipt_hint"
  } else {
    loginEmailClass.value = ""
  }
}

// admin pwd validate
function handleLoginPwd() {
  if (loginPwd.value.length > 0 && !pwdRegex.test(loginPwd.value)) {
    loginPwdClass.value = "ipt_hint"
  } else {
    loginPwdClass.value = ""
  }
}

// form lock
function handleFormLock() {
  if (emailRegex.test(loginEmail.value) && pwdRegex.test(loginPwd.value)) {
    btnClass.value = "";
  } else {
    btnClass.value = "btn_locked";
  }
}

// send login to API
function handleLogin() {
  formLoading.value = true

  axios.post(props.apiURL, {func: "Login", loginEmail: loginEmail.value, loginPwd: loginPwd.value})
    .then(result => processLogin({type: "returned", data: result.data}))
    .catch(err => processLogin({type: "error", data: err}))
}

// process API results
function processLogin({type, data}) {
  if (type === "returned") {
    if (typeof data === "object") {
        emit('emitLogin', data.adminName, data.adminID)
        toast.success("Login successful")
    } else if (data === "error email") {
      toast.error("Invalid email address submitted")
    } else if (data === "error pwd") {
      toast.error("Invalid password submitted") 
    } else if (data === "error not found") {
      toast.error("Login not recognised")
    }
    
  } else {
    formError.value = true
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
            <div class="popup_ttl">Login</div>
            <div class="popup_pad popup_pnl_v1">
                <div v-if="formError" class="popup_error">
                    <span>Connection Error</span>
                </div>
                <div v-else>
                  <div v-if="formLoading" class="popup_loading">
                      <BeatLoader color="#65A0C1"/>
                  </div>
                  <div v-else class="popup_form">
                      <form @submit.prevent="handleLogin()">
                      <p>
                          <label for="loginEmail">Email (username)</label>
                          <span :class="loginEmailClass">
                              <input type="text" name="loginEmail" id="loginEmail" value="" v-model="loginEmail" @keyup="handleLoginEmail();handleFormLock()">
                              <span class="ipt_hint_msg">name@domain.com</span>
                          </span>
                      </p>
                      <p>
                          <label for="loginPwd">Password</label>
                          <span :class="loginPwdClass">
                              <input type="password" name="loginPwd" id="loginPwd" value="" v-model="loginPwd" @keyup="handleLoginPwd();handleFormLock()">
                              <span class="ipt_hint_msg">Min 8 lowercase, UPPERCASE, numbers & special characters</span>
                          </span>
                      </p>
                      <p :class="btnClass"><button class="btn_blue">Submit</button></p>
                      </form>
                  </div>
                </div>
            </div>
        </div>
    </div>
</template>