<script setup>
import { reactive, onMounted } from "vue"
import axios from "axios"
import BeatLoader from "vue-spinner/src/BeatLoader.vue"
import { useToast } from "vue-toastification"

defineExpose({ remoteReload })
const props = defineProps({
    apiURL: String
})

// toast
const toast = useToast()

// state
const options = reactive({
    data: [],
    loading: false,
    error: false
});

// get options
function getOptions() {
    options.loading = true
    axios.post(props.apiURL, {func: "Get Options"})
        .then(results => processOptions({type: "returned", data: results.data}))
        .catch(err => processOptions({type: "error", data: err}))
}

function processOptions({type, data}) {
    if (type === "returned") {
        options.data = data
    } else {
        toast.error("Error loading options")
        options.error = true
        if (data.err) {
            console.log(data.err)
        }
    }

    options.loading = false
}

onMounted(async () => {
    getOptions()
})

// exposed function to reload column
function remoteReload() {
    getOptions()
}

// delete option
function handleDeleteOpt(optID) {
    if (confirm("Are you sure you want to delete this option?")) {
        options.loading = true
        axios.post(props.apiURL, {func: "Delete Option", optID: optID})
            .then(result => processDeleteOpt({type: "returned", data: result.data}))
            .catch(err => processDeleteOpt({type: "error", data: err.data}))
    }
}

function processDeleteOpt({type, data}) {
    if (type === "returned" && data === "deleted") {
        toast.success("Option deleted")
        getOptions()
    } else {
        toast.error("Option could not be deleted")
        if (data.err) {
            console.log(data.err)
        }
    }

    options.loading = false
}

// delete option value
function handleDeleteVal(valID) {
    if (confirm("Are you sure you want to delete this option value?")) {
        options.loading = true
        axios.post(props.apiURL, {func: "Delete Option Value", valID: valID})
            .then(result => processDeleteVal({type: "returned", data: result.data}))
            .catch(err => processDeleteVal({type: "error", data: err.data}))
    }
}

function processDeleteVal({type, data}) {
    if (type === "returned" && data === "deleted") {
        toast.success("Option value deleted")
        getOptions()
    } else {
        toast.error("Option value could not be deleted")
        if (data.err) {
            console.log(data.err)
        }
    }
}

</script>

<template>
    <section id="options">
        <div class="column_ttl column_ttl_dark">Item options</div>
        <div class="column_content">
            <div v-if="options.error" class="error">
                <span>Error loading options</span>
            </div>
            <div v-if="options.loading" class="loading">
                <BeatLoader color="#65A0C1"/>
            </div>
            <div v-else>
                <div class="column_opts align_right">
                    <RouterLink to="/option/add" class="nav_green">Add option</RouterLink>
                </div>
                <div class="column_list">
                    <ul>
                        <li v-for="option in options.data" :key="option">
                            <span v-if="option.type === 'opt'" class="opt_parent">
                                <span>{{ option.ovName }}</span>
                                <span class="column_li_opts">
                                    <RouterLink :to="`/option/edit/${option.ovID}`" class="nav_grey">Edit</RouterLink>
                                    <span v-if="option.canDelete === true">
                                        <a @click.prevent="handleDeleteOpt(option.ovID)" class="nav_red">Delete</a>
                                    </span>
                                    <span v-else>
                                        <span class="red_disabled">Delete</span>
                                    </span>
                                    <RouterLink :to="`/option/value/add/${option.ovID}`" class="nav_green">Add value</RouterLink>
                                </span>
                            </span>
                            <span v-else="option.type === 'val'">
                                <span>&raquo; {{ option.ovName }}</span>
                                <span class="column_li_opts">
                                    <RouterLink :to="`/option/value/edit/${option.ovID}`" class="nav_grey">Edit</RouterLink>
                                    <a @click.prevent="handleDeleteVal(option.ovID)" class="nav_red">Delete</a>
                                </span>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </section>
</template>