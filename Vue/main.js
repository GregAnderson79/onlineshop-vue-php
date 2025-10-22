import { createApp } from "vue"
import App from "./App.vue"
import router from "./router"
import Toast from "vue-toastification"
import "vue-toastification/dist/index.css"
import { createPinia } from "pinia"
import piniaPluginPersistedstate from "pinia-plugin-persistedstate"
import "./styles.css"

const app = createApp(App)
app.use(router)
app.use(Toast)
const pinia = createPinia()
pinia.use(piniaPluginPersistedstate)
app.use(pinia)
app.mount('#app')
