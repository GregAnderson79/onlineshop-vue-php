import { createRouter, createWebHistory} from 'vue-router'
import HomeView from '@/views/HomeView.vue'
import AddCatView from '@/views/AddCatView.vue'
import EditCatView from '@/views/EditCatView.vue'
import CatOrderView from '@/views/CatOrderView.vue'
import AddItemView from '@/views/AddItemView.vue'
import EditItemView from '@/views/EditItemView.vue'
import EditItemImgsView from '@/views/EditItemImgsView.vue'
import EditItemOptsView from '@/views/EditItemOptsView.vue'
import AddOptView from '@/views/AddOptView.vue'
import EditOptView from '@/views/EditOptView.vue'
import AddOptValView from '@/views/AddOptValView.vue'
import EditOptValView from '@/views/EditOptValView.vue'
import OrdersView from '@/views/OrdersView.vue'
import AdminView from '@/views/AdminView.vue'
import AddAdminView from '@/views/AddAdminView.vue'
import EditAdminView from '@/views/EditAdminView.vue'
import DelView from '@/views/DelView.vue'
import PayPalView from '@/views/PayPalView.vue'
import NotFoundView from '@/views/NotFoundView.vue'

const router = createRouter({
    history: createWebHistory(import.meta.env.BASE_URL),
    routes: [
        {
            path: '/',
            name: 'home',
            component: HomeView, 
        },
        {
            path: '/category/add',
            name: 'add category',
            component: AddCatView, 
        },
        {
            path: '/category/edit/:catID',
            name: 'edit category',
            component: EditCatView, 
        },
        {
            path: '/category/order',
            name: 'order main categories',
            component: CatOrderView, 
        },
        {
            path: '/category/order/:parID',
            name: 'order sub categories',
            component: CatOrderView, 
        },
        {
            path: '/item/add/:catID',
            name: 'add item',
            component: AddItemView, 
        },
        {
            path: '/item/edit/:itemID',
            name: 'edit item',
            component: EditItemView, 
        },
        {
            path: '/item/images/:itemID',
            name: 'edit item images',
            component: EditItemImgsView, 
        },
        {
            path: '/item/options/:itemID',
            name: 'edit item options',
            component: EditItemOptsView, 
        },
        {
            path: '/option/add',
            name: 'add option',
            component: AddOptView, 
        },
        {
            path: '/option/edit/:optID',
            name: 'edit option',
            component: EditOptView, 
        },
        {
            path: '/option/value/add/:optID',
            name: 'add option value',
            component: AddOptValView, 
        },
        {
            path: '/option/value/edit/:valID',
            name: 'edit option value',
            component: EditOptValView, 
        },
        {
            path: '/orders',
            name: 'orders',
            component: OrdersView, 
        },
        {
            path: '/admin',
            name: 'admin',
            component: AdminView, 
        },
        {
            path: '/admin/add',
            name: 'add admin',
            component: AddAdminView, 
        },
        {
            path: '/admin/edit/:adminID',
            name: 'edit admin',
            component: EditAdminView, 
        },
        {
            path: '/paypal',
            name: 'paypal',
            component: PayPalView, 
        },
        {
            path: '/delivery',
            name: 'delivery',
            component: DelView, 
        },
        {
            path: '/:catchAll(.*)',
            name: 'not-found',
            component: NotFoundView,
        },
    ],
})

export default router