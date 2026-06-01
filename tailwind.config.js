import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],

    theme: {
        extend: {
            fontFamily: {
                sans: ['Arial', 'Helvetica', 'sans-serif'],
            },
            colors: {
                primary: {
                    DEFAULT: '#16A34A', // Primary Green
                    light: '#22c55e',
                    dark: '#15803d',
                },
                soft: {
                    green: '#DCFCE7', // Soft Green
                },
                base: {
                    white: '#FFFFFF',
                    light: '#F3F4F6', // Light Gray
                    medium: '#9CA3AF', // Medium Gray
                    dark: '#374151', // Dark Gray Text
                }
            }
        },
    },

    plugins: [forms],
};
