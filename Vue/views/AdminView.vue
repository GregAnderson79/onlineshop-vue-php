<script setup>
import { reactive, onMounted } from "vue"
import axios from "axios";
import BeatLoader from "vue-spinner/src/BeatLoader.vue"
import { useToast } from "vue-toastification"

// props
const props = defineProps({
  apiURL: String
})

// toast
const toast = useToast()

// state
const admins = reactive({
  data: [],
  loading: false,
  error: false
})

// get admins from API
function getAdmins() {
  admins.loading = true
  axios.post(props.apiURL, {func: "Get Admins"})
    .then(results => processAdmins({type: "returned", data: results.data}))
    .catch(err => processAdmins({type: "error", data: err}))
}

function processAdmins({type, data}) {
  if (type === "returned" && Object.keys(data).length > 0) {
    admins.data = data
  } else {
    admins.error = true
    toast.error("Admin accounts could not be found")
  }

  admins.loading = false
}

onMounted(async () => {
  getAdmins()
})

// delete admin
function handleDeleteAdmin(adminID) {
  if (confirm("Are you sure you want to delete this admin account?")) {
    admins.loading = true

    axios.post(props.apiURL, {func: "Delete Admin", adminID: adminID})
      .then(result => processDeleteAdmin({type: "returned", data: result.data}))
      .catch(err => processDeleteAdmin({type: "error", data: err.data}))
  }
}

function processDeleteAdmin({type, data}) {
  if (type === "returned" && data === "deleted") {
    toast.success("Admin account deleted")
    getAdmins()
  } else {
    toast.error("Admin account could not be deleted")
    if (data.err) {
      console.log(data.err)
    }
  }

  admins.loading = false
}

</script>

<template>
  <div class="popup_bg">
    <div class="popup_pnl">
      <div class="popup_close"><RouterLink to="/">&#10005;</RouterLink></div>
      <div class="popup_ttl">Manage admin accounts</div>
      <div class="popup_pad popup_pnl_v2">
        <div v-if="admins.error" class="error">
          <span>Error loading orders</span>
        </div>
        <div v-if="admins.loading" class="loading">
          <BeatLoader color="#65A0C1"/>
        </div>
        <div v-else class="popup_list">
          <div class="popup_list_hdr">
            <RouterLink to="/admin/add" class="nav_green">Add admin account</RouterLink>
          </div>
          <ul>
            <li v-for="admin in admins.data" :key="admin">
              {{ admin.adminName }}
              <span class="popup_list_opts">
                <RouterLink :to="`/admin/edit/${admin.adminID}`" class="nav_grey">Edit</RouterLink>
                <a @click.prevent="handleDeleteAdmin(admin.adminID)" class="nav_red">Delete</a>
              </span>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>  
</template>
