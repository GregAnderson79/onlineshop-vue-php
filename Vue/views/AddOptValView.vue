<script setup>
import { ref, defineEmits } from "vue"
import { useRoute } from "vue-router"
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

// option id
const route = useRoute()
const optID = route.params.optID

// state
const valName = ref('')
const valNameClass = ref('')
const formLoading = ref(false)
const btnClass = ref('btn_locked')

// cat value name validate
function handleValName() {
  if (valName.value.length > 0 && valName.value.length < 3) {
    valNameClass.value = "ipt_hint"
  } else {
    valNameClass.value = ""
  }
}

// form lock
function handleFormLock() {
  if (valName.value.length >= 3) {
    btnClass.value = ""
  } else {
    btnClass.value = "btn_locked"
  }
}

// submit option value
function handleSubmit() {
  formLoading.value = true

  axios.post(props.apiURL, {func: "Add Option Value", valName: valName.value, optID: optID})
    .then(result => processSubmit({type: "returned", data: result.data}))
    .catch(err => processSubmit({type: "error", data: err}))
}

function processSubmit({type, data}) {
  if (type === "returned" && data === "added") {
    emit('emitReLoadOpts')
    router.push("/")
    toast.success("Option value added")

  } else if (type === "returned" && data === "error name") {
    toast.error("Option value name must be at least 3 characters")
  } else {
    toast.error("Option value could not be added")
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
      <div class="popup_ttl">Add option value</div>
      <div class="popup_pad popup_pnl_v1">
        <div v-if="formLoading" class="loading">
          <BeatLoader color="#65A0C1"/>
        </div>
        <div class="popup_form">
          <form @submit.prevent="handleSubmit()">
            <p>
              <label for="valName">Option value name</label>
              <span :class="valNameClass">
                <input type="text" name="valName" id="valName" value="" v-model="valName" @keyup="handleValName();handleFormLock()">
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
