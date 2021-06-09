import { createWebHistory, createRouter } from "vue-router";

import Home from "../pages/Home";
import Register from "../pages/Register";
import Login from "../pages/Login";
import Dashboard from "../pages/Dashboard";

import Bookings from "../components/Bookings";
import AddSlot from "../components/AddSlot";
import EditSlot from "../components/EditSlot";

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
        name: "books",
        path: "/books",
        component: Bookings
    },
    {
        name: "addbook",
        path: "/books/add",
        component: AddSlot
    },
    {
        name: "editbook",
        path: "/books/edit/:id",
        component: EditSlot
    }
];

const router = createRouter({
    history: createWebHistory(),
    routes: routes
});

export default router;
