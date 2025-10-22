<script setup>
import { ref, reactive, onMounted, defineEmits } from "vue"
import { useRoute } from "vue-router"
import router from "@/router"
import axios from "axios"
import BeatLoader from "vue-spinner/src/BeatLoader.vue"
import { useToast } from "vue-toastification"

// emits / props
const emit = defineEmits(["emitReLoadCols"])
const props = defineProps({
    apiURL: String
})

// toast
const toast = useToast()

// category id
const route = useRoute()
const catID = route.params.catID

// state
const mainCats = reactive({
    data: [],
    loading: false,
    error: false
})
const catName = ref('')
const catNameClass = ref('')
const parID = ref(0)
const catStatus = ref('active')
const catDesc = ref('')
const btnClass = ref('btn_locked')
const formLoading = ref(false)
const formError = ref(false)

// get main cats
function getMainCats() {
    mainCats.loading = true
    axios.post(props.apiURL, {func: "Get Main Categories"})
        .then(results => processMainCats({type: "returned", data: results.data}))
        .catch(err => processMainCats({type: "error", data: err}))
}

function processMainCats({type, data}) {
    if (type === "returned") {
        mainCats.data = data
    } else {
        mainCats.error = true
        if (data.err) {
            console.log(data.err)
        }
    }

    mainCats.loading = false
}

// get cat data
function getCatData() {
    formLoading.value = true
    axios.post(props.apiURL, {func: "Get Category", catID: catID})
        .then(results => processCatData({type: "returned", data: results.data}))
        .catch(err => processCatData({type: "error", data: err}))
}

function processCatData({type, data}) {
    if (type === "returned" && Object.keys(data).length > 0) {
        catName.value = data.catName
        parID.value = data.parID
        catStatus.value = data.catStatus
        catDesc.value = data.catDesc
    } else {
        formError.value = true
        toast.error("Category not found")
    }

    formLoading.value = false
}

onMounted(async () => {
    getMainCats()
    getCatData()
})

// cat name validate
function handleCatName() {
  if (catName.value.length > 0 && catName.value.length < 3) {
    catNameClass.value = "ipt_hint"
  } else {
    catNameClass.value = ""
  }
}

// form lock
function handleFormLock() {
  if (catName.value.length >= 3) {
    btnClass.value = ""
  } else {
    btnClass.value = "btn_locked"
  }
}

// update cat
function handleUpdate() {
  formLoading.value = true
  axios.post(props.apiURL, {func: "Update Category", catName: catName.value, parID: parID.value, catStatus: catStatus.value, catDesc: catDesc.value, catID: catID})
    .then(result => processUpdate({type: "returned", data: result.data}))
    .catch(err => processUpdate({type: "error", data: err}))
}

function processUpdate({type, data}) {
  if (type === "returned" && data === "updated") {
    toast.success("Category updated")
    emit('emitReLoadCols', 'cats')
    router.push("/")

  } else if (type === "returned" && data === "error name") {
    toast.error("Category names must have at least 3 characters")
  } else if (type === "returned" && data === "error self") {
    toast.error("Categories cannot be put inside themselves")
  } else if (type === "returned" && data === "error not found") {
    toast.error("Category not found")
  } else {
    toast.error("Category could not be updated")
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
      <div class="popup_ttl">Edit category</div>
      <div class="popup_pad popup_pnl_v2">
        <div v-if="formLoading" class="loading">
          <BeatLoader color="#65A0C1"/>
        </div>
        <div :class="[formError ? 'popup_form popup_error' : 'popup_form']">
          <form @submit.prevent="handleUpdate()">
            <p>
              <label for="catName">Category name</label>
              <span :class="catNameClass">
                <input type="text" name="catName" id="catName" value="" v-model="catName" @keyup="handleCatName();handleFormLock()">
                <span class="ipt_hint_msg">Minimum 3 characters</span>
              </span>
            </p>
            <p v-if="mainCats.loading">
              <BeatLoader color="#1E5D80" size="10px" />
            </p>  
            <p v-else>
              <label for="parID">Parent main category</label>
              <span>
                <select name="parID" id="parID" v-model="parID" @change="handleCatName();handleFormLock()">
                  <option value="0">No parent (new top level category)</option>
                  <option v-for="mainCat in mainCats.data" :key="mainCat" :value="mainCat.catID">
                    {{ mainCat.catName }}
                  </option>
                </select>
              </span>
            </p>
            <p>
              <label for="catStatus">Category status</label>
              <span>
                <select name="catStatus" id="catStatus" v-model="catStatus" @change="handleCatName();handleFormLock()">
                  <option value="active">Active</option>
                  <option value="hidden">Hidden</option>
                </select>
              </span>
            </p>
            <p>
              <label class="cat_desc_lbl" for="catDesc">Category<br>description</label>
              <span>
                <textarea name="catDesc" id="catDesc" v-model="catDesc" @keyup="handleCatName();handleFormLock()"></textarea>
              </span>
            </p>
            <p :class="btnClass"><button class="btn_blue">Submit</button></p>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
