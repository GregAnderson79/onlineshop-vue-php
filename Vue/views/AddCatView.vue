<script setup>
import { ref, reactive, onMounted, defineEmits } from "vue"
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
        mainCats.loading = false
    } else {
        mainCats.error = true
        if (data.err) {
          console.log(data.err)
        }
    }
}

onMounted(async () => {
    getMainCats()
})

// add new cat
function handleSubmit() {
  formLoading.value = true

  axios.post(props.apiURL, {func: "Add Category", catName: catName.value, parID: parID.value, catStatus: catStatus.value, catDesc: catDesc.value})
    .then(result => processSubmit({type: "returned", data: result.data}))
    .catch(err => processSubmit({type: "error", data: err}))
}

function processSubmit({type, data}) {
  if (type === "returned" && data === "added") {
    toast.success("Category added")
    emit('emitReLoadCols', 'cats')
    router.push("/")

  } else if (type === "returned" && data === "error name") {
    toast.error("Category names must have at least 3 characters")
  } else {
    toast.error("Category could not be added")
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
      <div class="popup_ttl">Add category</div>
      <div class="popup_pad popup_pnl_v2">
        <div v-if="formLoading.value" class="loading">
          <BeatLoader color="#65A0C1"/>
        </div>
        <div class="popup_form">
          <form @submit.prevent="handleSubmit()">
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
                <select name="parID" id="parID" v-model="parID">
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
                <select name="catStatus" id="catStatus" v-model="catStatus">
                  <option value="active">Active</option>
                  <option value="hidden">Hidden</option>
                </select>
              </span>
            </p>
            <p>
              <label class="cat_desc_lbl" for="catDesc">Category description</label>
              <span><textarea name="catDesc" id="catDesc" v-model="catDesc"></textarea></span>
            </p>
            <p :class="btnClass"><button class="btn_blue">Submit</button></p>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>
