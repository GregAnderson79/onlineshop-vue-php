<script setup>
import { ref, reactive, onMounted } from "vue"
import axios from "axios"
import BeatLoader from "vue-spinner/src/BeatLoader.vue"
import { useToast } from "vue-toastification"

// props, expose
defineExpose({ handleOpenMainCat, handleOpenSubCat, remoteReload })
const emit = defineEmits(["emitReLoadCols"])
const props = defineProps({
    apiURL: String
})

// toast
const toast = useToast()

// open panels -> MAIN, SUB, ITEMS
const openMainCat = ref()
const openSubCat = ref()
const openCols = ref(false)

// open main category -> sub, items panels
function handleOpenMainCat(catID) {
    openCols.value = true
    openMainCat.value = catID
    getSubCats(catID)
    getSubCatsColTitle(catID)
    getItems(catID)
    getItemsColTitle(catID)
}

// open sub category -> items panel
function handleOpenSubCat(catID) {
    openCols.value = true
    openSubCat.value = catID
    getItems(catID)
    getItemsColTitle(catID)
}


// ***** MAIN CATEGORIES *****
// state
const mainCats = reactive({
    data: [],
    length: 0,
    loading: false,
    error: false
})

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
        if (mainCats.data) {
            mainCats.length = mainCats.data.length
        } else {
            mainCats.length = 0
        }
    } else {
        mainCats.error = true
        if (data.err) {
            console.log(data.err)
        }
    }

    mainCats.loading = false
}

onMounted(async () => {
    getMainCats()
})


// ***** SUB-CATEGORIES *****
// state
const subCats = reactive({
    data: [],
    length: 0,
    loading: false,
    error: false
})

// get sub cats
function getSubCats(catID) {
    subCats.loading = true
    axios.post(props.apiURL, {func: "Get Sub Categories", catID: catID})
        .then(results => processSubCats({type: "returned", data: results.data}))
        .catch(err => processSubCats({type: "error", data: err}))
}

function processSubCats({type, data}) {
    if (type === "returned") {
        subCats.data = data
        if (subCats.data) {
            subCats.length = subCats.data.length
        } else {
            subCats.length = 0
        }
    } else {
        subCats.error = true
        if (data.err) {
            console.log(data.err)
        }
    }

    subCats.loading = false
}

// sub-cats column ttl state (main cat ttl / null)
const subCatsColTitle = reactive({
    name: '',
    loading: false,
    error: false
})

// get open main cat name for sub cat column title
function getSubCatsColTitle(catID) {
    subCatsColTitle.loading = true
    axios.post(props.apiURL, {func: "Get Category Name", catID: catID})
        .then(results => processSubCatsColTitle({type: "returned", data: results.data}))
        .catch(err => processSubCatsColTitle({type: "error", data: err}))
}

function processSubCatsColTitle({type, data}) {
    if (type === "returned") {
        subCatsColTitle.name = "in " + data.catName
    } else {
        subCatsColTitle.error = true
        if (data.err) {
            console.log(data.err)
        }
    }

    subCatsColTitle.loading = false
}

// ***** ITEMS *****
// state
const items = reactive({
    data: [],
    loading: false,
    error: false
})

// get items
function getItems(catID) {
    items.loading = true
    axios.post(props.apiURL, {func: "Get Items", catID: catID})
        .then(results => processItems({type: "returned", data: results.data}))
        .catch(err => processItems({type: "error", data: err}))
}

function processItems({type, data}) {
    if (type === "returned") {
        items.data = data
    } else {
        items.error = true
        if (data.err) {
            console.log(data.err)
        }
    }

    items.loading = false
}

// item column ttl state (main / sub cat ttl)
const itemsColTitle = reactive({
    name: '',
    loading: false
})

// get sub cat name for item column title
function getItemsColTitle(catID) {
    itemsColTitle.loading = true
    axios.post(props.apiURL, {func: "Get Category Name", catID: catID})
        .then(results => processItemsColTitle({type: "returned", data: results.data}))
        .catch(err => processItemsColTitle({type: "error", data: err}))
}

function processItemsColTitle({type, data}) {
    if (type === "returned") {
        itemsColTitle.name = "in " + data.catName   
    } else {
        itemsColTitle.error = true
        if (data.err) {
            console.log(data.err)
        }
    }

    itemsColTitle.loading = false
}

// delete cat
function handleDeleteCat(catID) {
    if (confirm("Are you sure you want to delete this category?")) {
        axios.post(props.apiURL, {func: "Delete Category", catID: catID})
            .then(result => processDeleteCat({type: "returned", data: result.data}))
            .catch(err => processDeleteCat({type: "error", data: err.data}))
    }
}

function processDeleteCat({type, data}) {
    if (type === "returned" && data === "deleted") {
        toast.success("Category deleted")
        emit('emitReLoadCols', 'cats')
    } else {
        toast.error("Category could not be deleted")
        if (data.err) {
            console.log(data.err)
        }
    }
}

// delete item
function handleDeleteItem(itemID) {
    if (confirm("Are you sure you want to delete this item?")){
        axios.post(props.apiURL, {func: "Delete Item", itemID: itemID})
            .then(result => processDeleteItem({type: "returned", data: result.data}))
            .catch(err => processDeleteItem({type: "error", data: err.data}))
    }
}

function processDeleteItem({type, data}) {
    if (type === "returned" && data === "deleted") {
        toast.success("Item deleted")
        emit('emitReLoadCols', 'items')
    } else {
        toast.error("Item could not be deleted")
        if (data.err) {
            console.log(data.err)
        }
    }
}

// exposed function to reload columns
function remoteReload(cols) {
    getMainCats()
    if (openMainCat.value) {
        getSubCats(openMainCat.value)
    }
    if (cols === "items") {
        if (openSubCat.value) {
            getItems(openSubCat.value)
        } else if (openMainCat.value) {
            getItems(openMainCat.value)
        }
    }
}

</script>

<template>
    <section id="mainCats">
        <div class="column_ttl column_ttl_dark">Main categories</div>
        <div class="column_content">
            <div v-if="mainCats.error" class="error">
                <span>Error loading main categories</span>
            </div>
            <div v-if="mainCats.loading" class="loading">
                <BeatLoader color="#65A0C1"/>
            </div>
            <div v-else>
                <div class="column_opts">
                    <RouterLink to="/category/order" class="nav_grey">Re-order categories</RouterLink>
                    <RouterLink to="/category/add" class="nav_green">Add category</RouterLink>
                </div>
                <div class="column_list">
                    <ul>
                        <li v-for="mainCat in mainCats.data" :key="mainCat">
                            <span>
                                <span>
                                    <a @click.prevent="handleOpenMainCat(mainCat.catID)">{{ mainCat.catName }}</a>
                                    <span v-if="mainCat.catStatus === 'hidden'" class="cat_hidden">(hidden)</span>
                                </span>
                                <span class="column_li_opts"> 
                                    <RouterLink :to="`/category/edit/${mainCat.catID}`" class="nav_grey">Edit</RouterLink>
                                    <span v-if="mainCat.canDelete === true">
                                        <a @click.prevent="handleDeleteCat(mainCat.catID)" class="nav_red">Delete</a>
                                    </span>
                                    <span v-else>
                                        <span class="red_disabled">Delete</span>
                                    </span>
                                </span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section id="subCats" v-if="openCols">
        <div class="column_ttl_light">
            <span class="column_hdr_arrow">&#10140;</span>
            <span v-if="subCatsColTitle.loading">
                <BeatLoader color="#1E5D80" size="10px" />
            </span>
            <span v-else>
                <span v-if="subCatsColTitle">Sub-categories {{ subCatsColTitle.name }}</span>
                <span v-else>Undefined category</span>
            </span>
        </div>
        <div class="column_content">
            <div v-if="subCats.error" class="error">
                <span>Error loading sub-categories</span>
            </div>
            <div v-if="subCats.loading" class="loading">
                <BeatLoader color="#65A0C1"/>
            </div>
            <div v-else>
                <div class="column_opts">
                    <RouterLink :to="`/category/order/${openMainCat}`" class="nav_grey">Re-order categories</RouterLink>
                    <RouterLink class="nav_green" :to="`/category/add/${openMainCat}`">Add category</RouterLink>
                </div>
                <div class="column_list">
                    <ul>
                        <li v-for="subCat in subCats.data" :key="subCat">
                            <span>
                                <span><a @click.prevent="handleOpenSubCat(subCat.catID)">{{ subCat.catName }}</a></span>
                                <span class="column_li_opts">
                                    <RouterLink :to="`/category/edit/${subCat.catID}`" class="nav_grey">Edit</RouterLink>
                                    <span v-if="subCat.canDelete === true">
                                        <a @click.prevent="handleDeleteCat(subCat.catID)" class="nav_red">Delete</a>
                                    </span>
                                    <span v-else>
                                        <span class="red_disabled">Delete</span>
                                    </span>
                                </span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
    <section id="items" v-if="openCols">
        <div class="column_ttl_lighter">
            <span class="column_hdr_arrow">&#10140;</span>
            <span v-if="itemsColTitle.loading">
                <BeatLoader color="#2E6D90" size="10px" />
            </span>
            <span v-else>
                <span v-if="itemsColTitle">Items {{ itemsColTitle.name }}</span>
                <span v-else>Items in undefined category</span>
            </span>
        </div>
        <div class="column_content">
            <div v-if="mainCats.error" class="error">
                <span>Error loading items</span>
            </div>
            <div v-if="items.loading" class="loading">
                <BeatLoader color="#65A0C1"/>
            </div>
            <div v-else>
                <div class="column_opts align_right">
                    <RouterLink :to="`/item/add/${openMainCat}`" class="nav_green">Add item</RouterLink>
                </div>
                <div class="column_list">
                    <ul>
                        <li v-for="item in items.data" :key="item">
                            <span>
                                <span>{{ item.itemName }}</span>
                                <span class="column_li_opts">
                                    <RouterLink :to="`/item/edit/${item.itemID}`" class="nav_grey">Edit</RouterLink>
                                    <RouterLink :to="`/item/images/${item.itemID}`" class="nav_grey">Images</RouterLink>
                                    <RouterLink :to="`/item/options/${item.itemID}`" class="nav_grey">Options</RouterLink>
                                    <a @click.prevent="handleDeleteItem(item.itemID)" class="nav_red">Delete</a>
                                </span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

</template>