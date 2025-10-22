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

// item id
const route = useRoute()
const itemID = route.params.itemID

// inputs
// state
const allCats = reactive({
    data: [],
    loading: false,
    error: false
})
const itemName = ref('')
const itemNameClass = ref('')
const catID = ref()
const itemPrice = ref(0)
const itemPriceClass = ref('')
const moneyRegex = /^\d+\.?\d{0,2}?$/;
const itemStock = ref(0)
const itemStockClass = ref('')
const itemStatus = ref('active')
const itemDesc1 = ref('')
const itemDesc2 = ref('')
const itemDesc3 = ref('')
const btnClass = ref('btn_locked')
const formLoading = ref(false)
const formError = ref(false)

// get all cats
function getAllCats() {
    allCats.loading = true
    axios.post(props.apiURL, {func: "Get All Categories List"})
        .then(results => processAllCats({type: "returned", data: results.data}))
        .catch(err => processAllCats({type: "error", data: err})) 
}

function processAllCats({type, data}) {
    if (type === "returned") {
        allCats.data = data
    } else {
        allCats.error = true
        if (data.err) {
            console.log(data.err)
        }
    }

    allCats.loading = false
}

// get item
function getItemData() {
    formLoading.value = true;
    axios.post(props.apiURL, {func: "Get Item", itemID: itemID})
        .then(results => processItemData({type: "returned", data: results.data}))
        .catch(err => processItemData({type: "error", data: err}))
}

function processItemData({type, data}) {
    if (type === "returned" && Object.keys(data).length > 0) {
        itemName.value = data?.itemName
        itemPrice.value = data?.itemPrice
        catID.value = data?.catID
        itemStatus.value = data?.itemStatus
        itemStock.value = data?.itemStock
        itemDesc1.value = data?.itemDesc1
        itemDesc2.value = data?.itemDesc2
        itemDesc3.value = data?.itemDesc3
    } else {
        formError.value = true
        toast.error("Item not found")
        if (data.err) {
            console.log(data.err)
        }
    }

    formLoading.value = false
}

// load main cats on mount & get cat data
onMounted(async () => {
    getAllCats()
    getItemData()
})

// item name validate
function handleItemName() {
  if (itemName.value.length > 0 && itemName.value.length < 3) {
    itemNameClass.value = "ipt_hint";
  } else {
    itemNameClass.value = "";
  }
}

// item price validate
function handleItemPrice() {
  if (itemPrice.value.length > 0 && !moneyRegex.test(itemPrice.value)) {
    itemPriceClass.value = "ipt_hint";
  } else {
    itemPriceClass.value = "";
  }
}

// stock validate
function handleItemStock() {
  if (Number.isInteger(+itemStock.value)) {
    itemStockClass.value = "";
  } else {
    itemStockClass.value = "ipt_hint";
  }
}

// form lock
function handleFormLock() {
  if (itemName.value.length >= 3 && moneyRegex.test(itemPrice.value) && Number.isInteger(+itemStock.value)) {
    btnClass.value = "";
  } else {
    btnClass.value = "btn_locked";
  }
}

// update item
// state
const updItem = reactive({
  data: {}
})

function handleUpdate() {
  formLoading.value = true
  updItem.data = {
    itemName: itemName.value,
    catID: catID.value,
    itemPrice: itemPrice.value,
    itemStatus: itemStatus.value,
    itemStock: itemStock.value,
    itemDesc1: itemDesc1.value,
    itemDesc2: itemDesc2.value,
    itemDesc3: itemDesc3.value,
    itemID: itemID
  }

  axios.post(props.apiURL, {func: "Update Item", data: updItem.data})
    .then(result => processUpdate({type: "returned", data: result.data}))
    .catch(err => processUpdate({type: "error", data: err}))
}

function processUpdate({type, data}) {
  if (type === "returned" && data === "updated") {
    toast.success("Item updated")
    emit('emitReLoadCols', 'items')
    router.push("/")

  } else if (type === "returned" && data === "error name") {
    toast.error("Item names must have at least 3 characters")
  } else if (type === "returned" && data === "error price") {
    toast.error("Item prices must be numbers with a 2 optional decimal places")
  } else if (type === "returned" && data === "error stock") {
    toast.error("Stock level must be a number")
  } else if (type === "returned" && data === "error ID") {
    toast.error("Item not found")
  } else {
    toast.error("Item could not be updated")
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
      <div class="popup_ttl">Edit item</div>
      <div class="popup_pad popup_pnl_v3">
        <div v-if="formLoading" class="loading">
          <BeatLoader color="#65A0C1"/>
        </div>
        <div :class="[formError ? 'popup_form popup_error' : 'popup_form']">
          <form @submit.prevent="handleUpdate()">
            <p>
              <label for="itemName">Item name</label>
              <span>
                <input type="text" name="itemName" id="itemName" value="" v-model="itemName" @keyup="handleItemName();handleFormLock()">
                <span class="ipt_hint_msg">Minimum 3 characters</span>
              </span>
            </p>
            <p v-if="allCats.loading">
              <BeatLoader color="#1E5D80" size="10px" />
            </p>
            <p v-else>
              <label for="catID">Category</label>
              <span>
                <select name="catID" id="catID" v-model="catID" @change="handleFormLock()">
                  <option v-for="allCat in allCats.data" :key="allCat" :value="allCat.catID">
                    {{ allCat.catName }}
                  </option>
                </select>
              </span>
            </p>
            <p>
              <label for="itemPrice">Item price</label>
              <span :class="itemPriceClass">
                <input type="text" name="itemPrice" id="itemPrice" value="" v-model="itemPrice" @keyup="handleItemPrice();handleFormLock()">
                <span class="ipt_hint_msg">Number formatted to 2 decimal places</span>
              </span>
            </p>
            <p>
              <label for="itemStatus">Item status</label>
              <span>
                <select name="itemStatus" id="itemStatus" v-model="itemStatus" @change="handleFormLock()">
                  <option value="active">Active</option>
                  <option value="hidden">Hidden</option>
                </select>
              </span>
            </p>
            <p>
              <label for="itemStock">Stock</label>
              <span :class="itemStockClass">
                <input type="text" name="itemStock" id="itemStock" value="" v-model="itemStock" @keyup="handleItemStock();handleFormLock()">
                <span class="ipt_hint_msg">Number required</span>
              </span>
            </p>
            <p>
              <label class="cat_desc_lbl" for="itemDesc1">Main item<br>description</label>
              <span>
                <textarea name="itemDesc1" id="itemDesc1" v-model="itemDesc1" @keyup="handleFormLock()"></textarea>
              </span>
            </p>
            <p>
              <label class="cat_desc_lbl" for="itemDesc2">Second<br>description</label>
              <span>
                <textarea name="itemDesc2" id="itemDesc2" style="height:100px" v-model="itemDesc2" @keyup="handleFormLock()"></textarea>
              </span>
            </p>
            <p>
              <label class="cat_desc_lbl" for="itemDesc3">Third<br>description</label>
              <span>
                <textarea name="itemDesc3" id="itemDesc3" style="height:100px" v-model="itemDesc3" @keyup="handleFormLock()"></textarea>
              </span>
            </p>
            <p :class="btnClass"><button class="btn_blue">Update</button></p>
          </form>
        </div>
      </div>
    </div>
  </div>  
</template>