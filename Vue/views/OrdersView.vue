<script setup>
import { reactive, onMounted } from "vue"
import axios from "axios"
import BeatLoader from "vue-spinner/src/BeatLoader.vue"
import { useToast } from "vue-toastification"

const props = defineProps({
    apiURL: String
})

// toast
const toast = useToast()

// state
const orders = reactive({
  data: {},
  loading: false,
  error: false
})

// get orders
function getOrders() {
  orders.loading = true
  axios.post(props.apiURL, {func: "Get Orders"})
    .then(results => processOrders({type: "returned", data: results.data}))
    .catch(err => processOrders({type: "error", data: err}))
}

function processOrders({type, data}) {
  if (type === "returned") {
    orders.data = data
  } else {
    orders.error = true
    toast.error("Error loading orders")
    if (data.err) {
      console.log(data.err)
    }
  }

  orders.loading = false
}

onMounted(async () => {
  getOrders()
})

</script>

<template>
  <div class="popup_bg">
    <div class="popup_big_pnl">
      <div class="popup_close"><RouterLink to="/">&#10005;</RouterLink></div>
      <div class="popup_ttl">Manage Orders</div>
      <div class="popup_pad popup_pnl_v3">
        <div v-if="orders.error" class="error">
          <span>Error loading orders</span>
        </div>
        <div v-if="orders.loading" class="loading">
          <BeatLoader color="#65A0C1"/>
        </div>
        <div v-else>
          <ul class="order_list">
            <li>
              <ul>
                <li><b>Order</b></li>
                <li><b>Name</b></li>
                <li><b>Contact details</b></li>
                <li><b>Address</b></li>
                <li><b>Items</b></li>
                <li><b>Total</b></li>
                <li><b>Status</b></li>
              </ul>
            </li>
            <li v-for="order in orders.data" :key="order">
              <ul>
                <li>
                  <span class="cart_hide">Order ID: </span>
                  {{ order.orderID }}
                </li>
                <li>
                  {{ order.firstName }} {{ order.lastName }}
                </li>
                <li>
                  {{ order.email }} 
                  <span class="cart_br">{{ order.tel }}</span>
                </li>
                <li>
                  {{ order.address1 }}, 
                  <span class="cart_br">{{ order.address2 }}, </span>
                  <span class="cart_br">{{ order.address2 }}, </span>
                  <span class="cart_br">{{ order.towncity }}, </span>
                  <span class="cart_br">{{ order.county }} </span>
                  <span class="cart_br">{{ order.postcode }} </span>        
                </li>
                <li>
                  <span v-for="item in order.items" :key="item">
                    {{ item.quantity }} x 
                    (item ID {{ item.itemID }}) 
                    {{ item.itemName }} 
                    @ {{ item.itemPrice }}...

                    <span v-if="item.options">
                      <span class="cart_br">Options:
                        <span v-for="(value, key, index) in item.options" :key="index">
                          ({{ key }} = {{ value }})
                        </span>
                      </span>
                    </span>
                    <br>&nbsp;<br>
                  </span>
                </li>
                <li>
                  <span class="cart_hide">Total: </span>
                  {{ order.total }}
                </li>
                <li>
                  <span class="cart_hide">Status: </span>
                  {{ order.orderStatus }}
                </li>
              </ul>
            </li>
          </ul>
        </div>
      </div>
    </div>
  </div>  
</template>
