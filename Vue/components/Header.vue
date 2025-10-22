<script setup>
import { ref, onMounted } from "vue"
import { RouterLink } from "vue-router"
import { useToast } from "vue-toastification"
import { userState } from "@/stores/user"

// emits
const emit = defineEmits(["emitLogOut"])

// logged in user
const user = userState()

// toast
const toast = useToast()

// log out
function handleLogOut() {
  if (confirm("Are you sure you want to logout?")) {
    toast.success("Logging out - goodbye")
    emit('emitLogOut')
  }
}

const userInitials = ref('')
onMounted(() => {
  userInitials.value = user.userLoginName.match(/(^\S\S?|\s\S)?/g).map(v=>v.trim()).join("").match(/(^\S|\S$)?/g).join("").toLocaleUpperCase()
})

</script>

<template>
  <header>
    <div class="hdr_title"><RouterLink to="/">Shop Admin</RouterLink></div>
    <div class="hdr_name">{{ user.userLoginName }}</div>
    <div class="hdr_initials">{{ userInitials }}</div>
    <a @click.prevent="handleLogOut()" class="nav_blue">Logout</a>
  </header>
</template>

