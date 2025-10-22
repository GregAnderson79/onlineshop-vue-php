<script setup>
import { ref, onMounted, defineEmits } from "vue"
import { useRoute } from "vue-router"
import router from "@/router"
import axios from "axios"
import BeatLoader from 'vue-spinner/src/BeatLoader.vue'
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
const optName = ref('')
const optNameClass = ref('')
const formLoading = ref(false)
const formError = ref(false)
const btnClass = ref('btn_locked')

// get option 
function getOptData() {
    formLoading.value = true
    axios.post(props.apiURL, {func: "Get Option", optID: optID})
        .then(results => processOptData({type: "returned", data: results.data}))
        .catch(err => processOptData({type: "error", data: err}))
}

function processOptData({type, data}) {
    if (type === "returned" && Object.keys(data).length > 0) {
        optName.value = data.optName  
    } else {
        formError.value = true
        toast.error("Option not found")
        if (data.err) {
            console.log(data.err)
        }
    }

    formLoading.value = false
}

onMounted(async () => {
    getOptData()
})

// cat name validate
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

// update option
function handleUpdate() {
  formLoading.value = true

  axios.post(props.apiURL, {func: "Update Option", optName: optName.value, optID: optID})
    .then(result => processUpdate({type: "returned", data: result.data}))
    .catch(err => processUpdate({type: "error", data: err}))
}

function processUpdate({type, data}) {
  if (type === "returned" && data === "updated") {   
    emit('emitReLoadOpts')
    router.push("/")
    toast.success("Option updated")

  } else if (type === "returned" && data === "error name") {
    toast.error("Option name must be at least 3 characters")
  } else {
    toast.error("Option could not be updated")
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
      <div class="popup_ttl">Edit option</div>
      <div class="popup_pad popup_pnl_v1">
        <div v-if="formLoading" class="loading">
          <BeatLoader color="#65A0C1"/>
        </div>
        <div :class="[formError ? 'popup_form popup_error' : 'popup_form']">
          <form @submit.prevent="handleUpdate()">
            <p>
              <label for="catName">Option name</label>
              <span :class="optNameClass">
                <input type="text" name="optName" id="optName" value="" v-model="optName" @keyup="handleOptName();handleFormLock()">
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
