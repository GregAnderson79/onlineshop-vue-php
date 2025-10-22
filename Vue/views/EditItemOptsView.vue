<script setup>
import { ref, reactive, onMounted, defineEmits } from "vue"
import { useRoute } from "vue-router"
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

// item id
const route = useRoute()
const itemID = route.params.itemID

// state
const formLoading = ref(false)
const options = reactive({
    data: [],
    loading: false,
    error: false
})

// get options 
function getOptions() {
    options.loading = true
    axios.post(props.apiURL, {func: "Get Options Assoc", itemID: itemID})
        .then(results => processOptions({type: "returned", data: results.data}))
        .catch(err => processOptions({type: "error", data: err}))
}

function processOptions({type, data}) {
    if (type === "returned") {
        options.data = data
    } else {
        options.error = true
        toast.error("Could not find options")
        if (data.err) {
            console.log(data.err)
        }
    }

    options.loading = false
}

onMounted(async () => {
    getOptions()
})

// add option assoc
function handleAddOpt(optID) {
  formLoading.value = true

  axios.post(props.apiURL, {func: "Add Option Assoc", optID: optID, itemID: itemID})
    .then(result => processAddOpt({type: "returned", data: result.data}))
    .catch(err => processAddOpt({type: "error", data: err}))
}

function processAddOpt({type, data}) {
  if (type === "returned" && data === "added") {
    getOptions()
  } else {
    toast.error("Option association could not be created")
  }

  formLoading.value = false
}

// remove option assoc
function handleRmvOpt(optID) {
  formLoading.value = true

  axios.post(props.apiURL, {func: "Remove Option Assoc", optID: optID, itemID: itemID})
    .then(result => processRmvOpt({type: "returned", data: result.data}))
    .catch(err => processRmvOpt({type: "error", data: err}))
}

function processRmvOpt({type, data}) {
  if (type === "returned" && data === "removed") {
    getOptions()
  } else {
    toast.error("Option association could not be removed")
    if (data.err) {
      console.log(data.err)
    }
  }

  formLoading.value = false
}

// add option value assoc
function handleAddVal(valID) {
  formLoading.value = true

  axios.post(props.apiURL, {func: "Add Option Value Assoc", valID: valID, itemID: itemID})
    .then(result => processAddVal({type: "returned", data: result.data}))
    .catch(err => processAddVal({type: "error", data: err}))
}

function processAddVal({type, data}) {
  if (type === "returned" && data === "added") {
    getOptions()
  } else {
    toast.error("Option value association could not be created")
    if (data.err) {
      console.log(data.err)
    }
  }

  formLoading.value = false
}

// remove option value assoc
function handleRmvVal(valID) {
  formLoading.value = true

  axios.post(props.apiURL, {func: "Remove Option Value Assoc", valID: valID, itemID: itemID})
    .then(result => processRmvVal({type: "returned", data: result.data}))
    .catch(err => processRmvVal({type: "error", data: err}))
}

function processRmvVal({type, data}) {
  if (type === "returned" && data === "removed") {
    getOptions()
  } else {
    toast.error("Option value association could not be removed")
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
      <div class="popup_ttl">Edit options</div>
      <div class="popup_pad popup_pnl_v3">
        <div v-if="options.error" class="error">
          <span>Error loading options</span>
        </div>
        <div v-if="options.loading" class="loading">
          <BeatLoader color="#65A0C1"/>
        </div>
        <div v-else class="column_list">
          <ul>
            <li v-for="option in options.data" :key="option">
              <span v-if="option.type === 'opt'" class="opt_parent">
                <span>{{ option.ovName }}</span>
                <span v-if="option.isAssoc === true && option.canRmv === true" class="column_li_opts">
                  <a @click.prevent="handleRmvOpt(option.ovID)" class="nav_red">Remove this option</a>
                </span>
                <span v-else-if="option.isAssoc === true && option.canRmv === false" class="column_li_opts">
                  <span class="red_disabled">Remove this option</span>
                </span>
                <span v-else class="column_li_opts">
                  <a @click.prevent="handleAddOpt(option.ovID)" class="nav_green">Add this option</a>
                </span>
              </span>
              <span v-else="option.type === 'val'">
                <span>&raquo; {{ option.ovName }}</span>
                <span v-if="option.isAssoc === true" class="column_li_opts">
                  <a @click.prevent="handleRmvVal(option.ovID)" class="nav_red">Remove this option value</a>
                </span>
                <span v-else class="column_li_opts">
                  <a @click.prevent="handleAddVal(option.ovID)" class="nav_green">Add this option value</a>
                </span>
              </span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>  
</template>
