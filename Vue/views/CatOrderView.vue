<script setup>
import { ref, onMounted, defineEmits } from "vue"
import { useRoute } from "vue-router"
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
const formLoading = ref(false)
const formError = ref(false)
const cats = ref()

// get parent cat ID (if sub)
const route = useRoute()
const parID = route.params.parID
const mainOrSub = ref('main')
if (parID) {
  mainOrSub.value = "sub"
}

// get cats
function getCats() {
  formLoading.value = true

  if (parID) {
    axios.post(props.apiURL, {func: "Get Sub Categories", catID: parID})
      .then(results => processCats({type: "returned", data: results.data}))
      .catch(err => processCats({type: "error", data: err}));
  } else {
    axios.post(props.apiURL, {func: "Get Main Categories"})
      .then(results => processCats({type: "returned", data: results.data}))
      .catch(err => processCats({type: "error", data: err}));
  }
}

function processCats({type, data}) {
    if (type === "returned") {
      cats.value = data
    } else {
      toast.error("Categories not found")
      formError.value = true
      if (data.err) {
          console.log(data.err)
      }
    }

    formLoading.value = false
}

// load cats on mount
onMounted(async () => {
    getCats()
})

// click buttons
function handleClick(catID, dir) {
  formLoading.value = true

  axios.post(props.apiURL, {func: "Update Category Order", catID: catID, dir: dir})
    .then(result => processUpdate({type: "returned", data: result.data}))
    .catch(err => processUpdate({type: "error", data: err}))
}

function processUpdate({type, data}) {
  if (type === "returned" && data === "updated") {
    getCats()
    emit('emitReLoadCols', 'cats')

  } else if (type === "returned" && data === "error not found") {
    toast.error("Categories not found")
  } else if (type === "returned" && data === "error not moved") {
    toast.error("Category could not be moved")
  } else {
    toast.error("Category could not be moved")
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
      <div class="popup_ttl">Re-order {{ mainOrSub }} categories</div>
      <div class="popup_pad popup_pnl_v2">
        <div v-if="formError" class="popup_error">
          <span>Error</span>
        </div>
        <div v-else>
          <div v-if="formLoading" class="popup_loading">
            <BeatLoader color="#65A0C1"/>
          </div>
          <div v-else class="popup_list">
            <ul>
              <li v-for="cat in cats" :key="cat" :value="cat.catID">
                {{ cat.catName }}
                <span class="column_li_opts">
                  <a @click.prevent="handleClick(cat.catID, 'up')" :aria-label="`Move the ${cat.catName} category up 1 position`" class="nav_grey">Up</a>
                  <a @click.prevent="handleClick(cat.catID, 'down')" :aria-label="`Move the ${cat.catName} category down 1 position`" class="nav_grey">Down</a>
                </span>
              </li>
            </ul>
          </div>
        </div>
      </div>
    </div>
  </div>  
</template>
