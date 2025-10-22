<script setup>
import { ref } from "vue"
import { RouterView } from "vue-router"
import { userState } from "@/stores/user"
import Header from "@/components/Header.vue"
import MobileNav from "@/components/MobileNav.vue"
import ItemColumns from "@/components/ItemColumns.vue"
import OptionColumn from "@/components/OptionColumn.vue"
import ManageColumn from "@/components/ManageColumn.vue"
import Login from "@/components/Login.vue"

// logged in user
const user = userState()

// emits / props
const apiURL = "https://**********/endpoints.php";
const itemColumns = ref()
const mobileNav = ref()
const optionColumn = ref()

function emitOpenMainCat(catID) {
  itemColumns.value.handleOpenMainCat(catID)
}

function emitOpenSubCat(catID) {
  itemColumns.value.handleOpenSubCat(catID)
}

function emitReLoadCols(cols) {
  itemColumns.value.remoteReload(cols)
  mobileNav.value.getMobNav()
}

function emitReLoadOpts() {
  optionColumn.value.remoteReload()
}

function emitLogin(loggedInName, loggedInID) {
  user.userLogin(loggedInName, loggedInID)
}

function emitLogOut() {
  user.userLogOut()
}

</script>

<template>
  <div v-if="user.userLoginStatus">
    <Header
      v-on:emitLogOut="emitLogOut"
      :apiURL="apiURL"
    />
    <MobileNav
      ref="mobileNav"
      v-on:emitOpenMainCat="emitOpenMainCat" 
      v-on:emitOpenSubCat="emitOpenSubCat" 
      :apiURL="apiURL" 
    />
    <main>
      <ItemColumns 
        ref="itemColumns"
        :apiURL="apiURL"
        v-on:emitReLoadCols="emitReLoadCols"
      />
      <OptionColumn 
        ref="optionColumn"
        :apiURL="apiURL"
        v-on:emitReLoadOpts="emitReLoadOpts"
      />
      <ManageColumn />
    </main>
    <RouterView 
      v-on:emitReLoadCols="emitReLoadCols"
      v-on:emitReLoadOpts="emitReLoadOpts"
      :apiURL="apiURL"
    />
  </div>
  <div v-else>
    <Login
      v-on:emitLogin="emitLogin"
      :apiURL="apiURL"
    />
  </div>
</template>

