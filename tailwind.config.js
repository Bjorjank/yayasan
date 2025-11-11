// tailwind.config.js (atau tailwind.config.cjs)
import defaultTheme from "tailwindcss/defaultTheme";
import forms from "@tailwindcss/forms";

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php",
        "./storage/framework/views/*.php",
        "./resources/views/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.ts",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            colors: {
                brand: {
                    blue: {
                        DEFAULT: "#145EFC", // 600
                        50: "#eef4ff",
                        100: "#dce7ff",
                        200: "#b3ccff",
                        300: "#85adff",
                        400: "#4f86ff",
                        500: "#2a6dff",
                        600: "#145EFC", // utama
                        700: "#0f4fd2",
                        800: "#0b3ea6",
                        900: "#072a73",
                    },
                    red: {
                        DEFAULT: "#D21F26", // 600
                        50: "#fff1f2",
                        100: "#ffe4e6",
                        200: "#fecdd3",
                        300: "#fda4af",
                        400: "#fb7185",
                        500: "#f43f5e",
                        600: "#D21F26", // utama
                        700: "#ab1920",
                        800: "#861319",
                        900: "#5f0d12",
                    },
                },
            },
            fontFamily: {
                sans: ["Figtree", ...defaultTheme.fontFamily.sans],
            },
        },
    },
    plugins: [forms],
};
