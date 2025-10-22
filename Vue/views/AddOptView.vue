<script setup>
import { ref, defineEmits } from "vue"
import router from "@/router"
import axios from "axios"
import BeatLoader from "vue-spinner/src/BeatLoader.vue"
import { useToast } from "vue-toastification"

// emits / props
const emit = defineEmits(["emitReLoadOpts"])
const props = defineProps({
    apiURL: String
})

// toast
const toast = useToast()

// inputs
const optName = ref('')
const optNameClass = ref('')
const btnClass = ref('btn_locked')
const formLoading = ref(false)

// option name validate
function handleOptName() {
  if (optName.value.length > 0 && optName.value.length < 3) {
    optNameClass.value = "ipt_hint"
  } else {
    optNameClass.value = ""
  }
}

// form lock
function handleFormLock() {
  if (optName.value.length >= 3) {
    btnClass.value = ""
  } else {
    btnClass.value = "btn_locked"
  }
}

// add option
function handleSubmit() {
  formLoading.value = true

  axios.post(props.apiURL, {func: "Add Option", optName: optName.value})
    .then(result => processSubmit({type: "returned", data: result.data}))
    .catch(err => processSubmit({type: "error", data: err}))
}

function processSubmit({type, data}) {
  if (type === "returned" && data === "added") {
    emit('emitReLoadOpts')
    router.push("/")
    toast.success("Option added")

  } else if (type === "returned" && data === "error name") {
    toast.error("Option name must be at least 3 characters")
  } else {
    toast.error("Option could not be added")
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
      <div class="popup_ttl">Add option</div>
      <div class="popup_pad popup_pnl_v1">
        <div v-if="formLoading" class="loading">
          <BeatLoader color="#65A0C1"/>
        </div>
        <div class="popup_form">
          <form @submit.prevent="handleSubmit()">
            <p>
              <label for="optName">Option name</label>
              <span :class="optNameClass">
                <input type="text" name="optName" id="optName" value="" v-model="optName" @keyup="handleOptName();handleFormLock()">
                <span class="ipt_hint_msg">Minimum 3 characters</span>
              </span>
            </p>
            <p :class="btnClass"><button class="btn_blue">Submit</button></p>
          </form>
        </div>
      </div>
    </div>
  </div>  
</template>
