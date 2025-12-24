import { createRouter, createWebHistory } from "vue-router";
import Login from "../views/Login.vue";
import Dashboard from "../views/Dashboard.vue";
import RequestPassword from "../views/RequestPassword.vue";
import ResetPassword from "../views/ResetPassword.vue";
import AppLayout from "../components/AppLayout.vue";
import CustomerLayout from "../layouts/CustomerLayout.vue"; // ✅ Add this
import Products from "../views/Products.vue";
import ProductForm from "../views/ProductForm.vue";
import store from "../store";
import NotFound from "../views/NotFound.vue";
import Categories from "../views/Categories.vue";
import Users from "../views/Users.vue";
import Register from "../views/Register.vue";
import VerifyEmail from "../views/VerifyEmail.vue";
import Shop from "../views/Shop.vue";

const routes = [
    // Redirect root to shop
    {
        path: "/",
        redirect: "/shop",
    },

    // Auth Routes (No Layout - Standalone)
    {
        path: "/login",
        name: "login",
        meta: {
            requiresGuest: true,
        },
        component: Login,
    },
    {
        path: "/register",
        name: "register",
        meta: {
            requiresGuest: true,
        },
        component: Register,
    },
    {
        path: "/verify-email",
        name: "verify-email",
        meta: {
            requiresGuest: true,
        },
        component: VerifyEmail,
    },
    {
        path: "/request-password",
        name: "requestPassword",
        meta: {
            requiresGuest: true,
        },
        component: RequestPassword,
    },
    {
        path: "/reset-password/:token",
        name: "resetPassword",
        meta: {
            requiresGuest: true,
        },
        component: ResetPassword,
    },

    // ✅ Customer-Facing Routes (CustomerLayout with Navigation)
    {
        path: "/",
        component: CustomerLayout,
        children: [
            {
                path: "shop",
                name: "shop",
                component: Shop,
            },
            {
                path: "/payment/success",
                name: "payment-success",
                component: () => import("../views/PaymentSuccess.vue"),
            },
            {
                path: "/payment/cancel",
                name: "payment-cancel",
                component: () => import("../views/PaymentCancel.vue"),
            },
            {
                path: "cart",
                name: "cart",
                component: () => import("../views/Cart.vue"),
            },
            {
                path: "favorites",
                name: "favorites",
                component: () => import("../views/Favorites.vue"),
            },
            {
                path: "island-hub",
                name: "island-hub",
                meta: { hideNavbar: true },
                component: () => import("../views/IslandHub.vue"),
            },
            {
                path: "games",
                name: "games",
                component: () => import("../views/Games.vue"),
            },
            {
                path: "runes",
                name: "runes",
                component: () => import("../views/Runes.vue"),
            },
            {
                path: "quests",
                name: "quests",
                component: () => import("../views/Quests.vue"),
            },
            {
                path: "specials",
                name: "specials",
                component: () => import("../views/Specials.vue"),
            },
            {
                path: "contact",
                name: "contact",
                component: () => import("../views/Contact.vue"),
            },
            {
                path: "treasury",
                name: "treasury",
                component: () => import("../views/Treasury.vue"),
            },
            {
                path: "rune-exchange",
                name: "rune-exchange",
                component: () => import("../views/RuneExchange.vue"),
            },
            {
                path: "product/:slug",
                name: "product",
                component: () => import("../views/ProductDetail.vue"),
            },
            {
                path: "profile",
                name: "profile",
                meta: { requiresAuth: true },
                component: () => import("../views/Profile.vue"),
            },
            {
                path: "orders",
                name: "orders",
                meta: { requiresAuth: true },
                component: () => import("../views/Orders.vue"),
            },
            {
                path: "orders/:id",
                name: "order-detail",
                meta: { requiresAuth: true },
                component: () => import("../views/OrderConfirmation.vue"),
            },
            {
                path: "checkout",
                name: "checkout",
                meta: { requiresAuth: true },
                component: () => import("../views/Checkout.vue"),
            },
        ],
    },

    // Admin Routes (AppLayout - Your Existing Structure)
    {
        path: "/app",
        name: "app",
        component: AppLayout,
        meta: {
            requiresAuth: true,
        },
        children: [
            {
                path: "dashboard",
                name: "app.dashboard",
                component: Dashboard,
            },
            {
                path: "products",
                name: "app.products",
                component: Products,
                meta: { requiresAdmin: true },
            },
            {
                path: "products/create",
                name: "app.products.create",
                component: ProductForm,
                meta: { requiresAdmin: true },
            },
            {
                path: "products/:id",
                name: "app.products.edit",
                component: ProductForm,
                meta: { requiresAdmin: true },
            },
            {
                path: "categories",
                name: "app.categories",
                component: Categories,
                meta: { requiresAdmin: true },
            },
            {
                path: "users",
                name: "app.users",
                component: Users,
                meta: { requiresAdmin: true },
            },
        ],
    },

    // 404 Not Found (Keep at the end)
    {
        path: "/:pathMatch(.*)",
        name: "notfound",
        component: NotFound,
    },
];

const router = createRouter({
    history: createWebHistory(),
    routes,
});

// ✅ Keep your existing beforeEach logic
router.beforeEach(async (to, from, next) => {
    // If user has token but no user data, fetch it
    if (store.state.user.token && !store.state.user.data.id) {
        try {
            await store.dispatch("getCurrentUser");
        } catch (error) {
            console.error("Failed to get user:", error);
            store.commit("logout");
            if (to.meta.requiresAuth) {
                return next({ name: "login" });
            }
        }
    }

    // Check if admin required
    if (to.meta.requiresAdmin && !store.getters.isAdmin) {
        return next({ name: "app.dashboard" });
    }

    // Check auth requirements
    if (to.meta.requiresAuth && !store.state.user.token) {
        next({ name: "login" });
    } else if (to.meta.requiresGuest && store.state.user.token) {
        next({ name: "app.dashboard" });
    } else {
        next();
    }
});

export default router;
