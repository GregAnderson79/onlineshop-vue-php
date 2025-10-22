<script setup>
import { ref, onMounted, defineEmits } from "vue"
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
const valID = route.params.valID

// state
const valName = ref('')
const valNameClass = ref('')
const formLoading = ref(false)
const formError = ref(false)
const btnClass = ref('btn_locked')

// get option value
function getValData() {
  formLoading.value = true

  axios.post(props.apiURL, {func: "Get Option Value", valID: valID})
      .then(results => processValData({type: "returned", data: results.data}))
      .catch(err => processValData({type: "error", data: err}))
}

function processValData({type, data}) {
  if (type === "returned" && Object.keys(data).length > 0) {
    valName.value = data.valName
  } else {
    formError.value = true
    toast.error("Option value not found")
    if (data.err) {
      console.log(data.err)
    }
  }

  formLoading.value = false
}

onMounted(async () => {
    getValData()
})

// option value name validate
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

// update option value
function handleUpdate() {
  formLoading.value = true

  axios.post(props.apiURL, {func: "Update Option Value", valName: valName.value, valID: valID})
    .then(result => processUpdate({type: "returned", data: result.data}))
    .catch(err => processUpdate({type: "error", data: err}))
}

function processUpdate({type, data}) {
  if (type === "returned" && data === "updated") {   
    emit('emitReLoadOpts')
    router.push("/")
    toast.success("Option value updated")

  } else if (type === "returned" && data === "error name") {
    toast.error("Option value name must be at least 3 characters")
  } else {
    toast.error("Option value could not be updated")
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
      <div class="popup_ttl">Edit option value</div>
      <div class="popup_pad popup_pnl_v1">
        <div v-if="formLoading" class="loading">
          <BeatLoader color="#65A0C1"/>
        </div>
        <div :class="[formError ? 'popup_form popup_error' : 'popup_form']">
          <form @submit.prevent="handleUpdate()">
            <p>
              <label for="valName">Option value name</label>
              <span :class="valNameClass">
                <input type="text" name="valName" id="valName" value="" v-model="valName" @keyup="handleValName();handleFormLock()">
                <span class="ipt_hint_msg">Minimum 3 characters</span>
              </span>
            </p>
            <p :class="btnClass"><button class="btn_blue">Update</button></p>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
