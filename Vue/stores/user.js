import { defineStore } from "pinia"

export const userState = defineStore('user', {
    state: () => ({
        userLoginStatus: false,
        userLoginName: "",
        userLoginID: ""
    }),
    actions: {
        userLogin(name, ID) {
            this.userLoginStatus = true,
            this.userLoginName = name,
            this.userLoginID = ID
        },

        userLogOut() {
            this.userLoginStatus = false,
            this.userLoginName = "",
            this.userLoginID = null
        }
    },
    persist: true
  })
