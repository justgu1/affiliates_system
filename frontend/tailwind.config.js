/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./index.html",
    "./src/**/*.{js,ts,jsx,tsx,vue}",
  ],
  theme: {
    extend: {
      colors: {
        featured_light: '#01feae',
        featured_dark: '#02be83',
      }
    },
  },
  plugins: [],
}

