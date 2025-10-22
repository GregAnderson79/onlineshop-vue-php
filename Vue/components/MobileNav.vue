<script setup>
import { ref, reactive, onMounted } from "vue"
import axios from "axios"
import BeatLoader from "vue-spinner/src/BeatLoader.vue"
import { useToast } from "vue-toastification"

// emits, expose, props
defineExpose({ getMobNav })
const props = defineProps({ apiURL: String })
const emit = defineEmits(['emitOpenMainCat', 'emitOpenSubCat'])

// toast
const toast = useToast()

// open, close mobile nav
const MNstate = ref("mob_nav mn_closed")

function handleCloseMN() {
    MNstate.value = "mob_nav mn_closed"
}

function handleOpenMN() {
    MNstate.value = "mob_nav"
}

// state
const links = reactive({
    data: [],
    loading: false,
    error: false
})

// get main cats from API
function getMobNav() {
    links.loading = true
    axios.post(props.apiURL, {func: "Get Mobile Nav"})
        .then(results => processMobNav({type: "returned", data: results.data}))
        .catch(err => processMobNav({type: "error", data: err}))
}

function processMobNav({type, data}) {
    if (type === "returned") {
        links.data = data
    } else {
        links.error = true
        if (data.err) {
            console.log(data.err)
        }
    }

    links.loading = false
}

onMounted(async () => {
    getMobNav()
})

// delete cat
function handleDeleteCat(catID) {
    if (confirm("Are you sure you want to delete this category?")) {
        links.loading = true
        axios.post(props.apiURL, {func: "Delete Category", catID: catID})
            .then(result => processDeleteCat({type: "returned", data: result.data}))
            .catch(err => processDeleteCat({type: "error", data: err.data}))
    }
}

function processDeleteCat({type, data}) {
    if (type === "returned" && data === "deleted") {
        toast.success("Category deleted")
        getMobNav()
    } else {
        toast.error("Category could not be deleted")
        if (data.err) {
            console.log(data.err)
        }
    }

    links.loading = false
}

</script>

<template>
    <div class="mob_nav_hgr">
        <a @click.prevent="handleOpenMN()" class="mob_nav_a" aria-label="Open mobile main category > sub category navigation">
            <span></span><span></span><span></span>
        </a>
    </div>
    <div :class="MNstate">
        <div class="mob_nav_hdr">
            <RouterLink to="/category/order" @click.prevent="handleCloseMN()" class="nav_grey">Re-order categories</RouterLink>
            <RouterLink to="/category/add" @click.prevent="handleCloseMN()" class="nav_green">Add category</RouterLink>
            <a @click.prevent="handleCloseMN()" class="mob_nav_close" aria-label="Close mobile main category > sub category navigation">&#10005;</a>
        </div>
        <div class="mob_nav_scr">
            <div v-if="links.error" class="mob_nav_error">
                <span>Error loading mobile navigation</span>
            </div>
            <div v-else>
                <div v-if="links.loading" class="pnl_loading">
                    <BeatLoader color="#65A0C1"/>
                </div>
                <nav v-else>
                    <ul>
                        <li v-for="nav in links.data" :key="nav">
                            <span v-if="nav.type === 'main'">
                                <span class="MN_parent">
                                    <span>
                                        <a @click.prevent="$emit('emitOpenMainCat',nav.catID);handleCloseMN()">{{ nav.catName }}</a>
                                        <span v-if="nav.catStatus === 'hidden'" class="cat_hidden">(hidden)</span>
                                    </span>
                                    <span class="column_li_opts"> 
                                        <RouterLink :to="`/category/edit/${nav.catID}`" @click.prevent="handleCloseMN()" class="nav_grey">Edit</RouterLink>
                                        <span v-if="nav.canDelete === true">
                                            <a @click.prevent="handleDeleteCat(nav.catID)" class="nav_red">Delete</a>
                                        </span>
                                        <span v-else>
                                            <span class="red_disabled">Delete</span>
                                        </span>
                                    </span>
                                </span>
                            </span>
                            <span v-else>
                                <span>
                                    <span> 
                                        <a @click.prevent="$emit('emitOpenSubCat',nav.catID);handleCloseMN()"> - {{ nav.catName }}</a>
                                        <span v-if="nav.catStatus === 'hidden'" class="cat_hidden">(hidden)</span>
                                    </span>
                                    <span class="column_li_opts"> 
                                        <RouterLink :to="`/category/edit/${nav.catID}`" @click.prevent="handleCloseMN()" class="nav_grey">Edit</RouterLink>
                                        <span v-if="nav.canDelete === true">
                                            <a @click.prevent="handleDeleteCat(nav.catID)" class="nav_red">Delete</a>
                                        </span>
                                        <span v-else>
                                            <span class="red_disabled">Delete</span>
                                        </span>
                                    </span>
                                </span>
                            </span>
                        </li>
                    </ul>
                </nav>
            </div>
            <div class="mob_nav_management">
                <nav>
                    <ul>
                    <li><RouterLink to="/orders" @click.prevent="handleCloseMN()" class="MN_RouterLink">Manage Orders</RouterLink></li>
                    <li><RouterLink to="/admins" @click.prevent="handleCloseMN()" class="MN_RouterLink">Manage admin accounts</RouterLink></li>
                    <li><RouterLink to="/paypal" @click.prevent="handleCloseMN()" class="MN_RouterLink">Manage PayPal email</RouterLink></li>
                    <li><RouterLink to="/delivery" @click.prevent="handleCloseMN()" class="MN_RouterLink">Manage delivery price</RouterLink></li>
                    </ul>
                </nav>
            </div>
        </div>
    </div>
</template>