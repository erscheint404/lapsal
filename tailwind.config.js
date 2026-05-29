/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    darkMode: 'class',
    theme: {
        extend: {
            colors: {
                primary: {
                    50: '#f7ffe0',
                    100: '#edffc2',
                    200: '#dcff8a',
                    300: '#ccff00',
                    400: '#b8e600',
                    500: '#a3cc00',
                    600: '#8ab300',
                    700: '#6e8f00',
                    800: '#526b00',
                    900: '#374700',
                    950: '#1c2400',
                },
                accent: {
                    50: '#e0feff',
                    100: '#b3fcff',
                    200: '#80f9ff',
                    300: '#00e5ff',
                    400: '#00cce6',
                    500: '#00b3cc',
                    600: '#0099b3',
                    700: '#007a8f',
                    800: '#005c6b',
                    900: '#003d47',
                    950: '#001f24',
                },
                dark: {
                    50: '#f0f4f8',
                    100: '#dce4ee',
                    200: '#b8c9dd',
                    300: '#8da3bd',
                    400: '#627d9e',
                    500: '#46607f',
                    600: '#344a63',
                    700: '#263750',
                    800: '#1a2740',
                    900: '#0f1b2e',
                    950: '#0a1221',
                },
            },
            fontFamily: {
                sans: ['DM Sans', 'system-ui', '-apple-system', 'sans-serif'],
                display: ['Satoshi', 'DM Sans', 'system-ui', 'sans-serif'],
            },
            animation: {
                'fade-in': 'fadeIn 0.5s ease-out',
                'fade-in-up': 'fadeInUp 0.6s ease-out',
                'slide-in-left': 'slideInLeft 0.5s ease-out',
                'slide-in-right': 'slideInRight 0.5s ease-out',
                'pulse-slow': 'pulse 3s cubic-bezier(0.4, 0, 0.6, 1) infinite',
                'bounce-slow': 'bounce 2s infinite',
                'shimmer': 'shimmer 2s infinite linear',
                'count-up': 'countUp 1s ease-out',
                'float': 'float 6s ease-in-out infinite',
                'float-slow': 'float 8s ease-in-out infinite',
                'pulse-glow': 'pulseGlow 3s ease-in-out infinite',
                'gradient-shift': 'gradientShift 8s ease infinite',
                'spin-slow': 'spin 12s linear infinite',
            },
            keyframes: {
                fadeIn: {
                    '0%': { opacity: '0' },
                    '100%': { opacity: '1' },
                },
                fadeInUp: {
                    '0%': { opacity: '0', transform: 'translateY(30px)' },
                    '100%': { opacity: '1', transform: 'translateY(0)' },
                },
                slideInLeft: {
                    '0%': { opacity: '0', transform: 'translateX(-30px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                slideInRight: {
                    '0%': { opacity: '0', transform: 'translateX(30px)' },
                    '100%': { opacity: '1', transform: 'translateX(0)' },
                },
                shimmer: {
                    '0%': { backgroundPosition: '-200% 0' },
                    '100%': { backgroundPosition: '200% 0' },
                },
                float: {
                    '0%, 100%': { transform: 'translateY(0px)' },
                    '50%': { transform: 'translateY(-20px)' },
                },
                pulseGlow: {
                    '0%, 100%': { boxShadow: '0 0 20px rgba(204, 255, 0, 0.1)' },
                    '50%': { boxShadow: '0 0 40px rgba(204, 255, 0, 0.25)' },
                },
                gradientShift: {
                    '0%': { backgroundPosition: '0% 50%' },
                    '50%': { backgroundPosition: '100% 50%' },
                    '100%': { backgroundPosition: '0% 50%' },
                },
            },
            backgroundImage: {
                'gradient-radial': 'radial-gradient(var(--tw-gradient-stops))',
            },
        },
    },
    plugins: [],
}
