import { createWebHistory, createRouter } from "vue-router";

import Home from "../pages/Home";
import Register from "../pages/Register";
import Login from "../pages/Login";
import Dashboard from "../pages/Dashboard";

import Bookings from "../components/Bookings";
import AddBooking from "../components/AddBooking";
import EditBooking from "../components/EditBooking";

export const routes = [
    {
        name: "home",
        path: "/",
        component: Home
    },
    {
        name: "register",
        path: "/register",
        component: Register
    },
    {
        name: "login",
        path: "/login",
        component: Login
    },
    {
        name: "dashboard",
        path: "/dashboard",
        component: Dashboard
    },
    {
        name: "bookings",
        path: "/bookings",
        component: Bookings
    },
    {
        name: "addbooking",
        path: "/booking/add",
        component: AddBooking
    },
    {
        name: "editbooking",
        path: "/booking/edit/:id",
        component: EditBooking
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes
});

export default router;
