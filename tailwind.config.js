/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
    "./node_modules/flowbite/**/*.js"
  ],
  theme: {
    extend: {
      width: {
        "128": "32rem",
        "144": "36rem",
        "156": "40rem"
      }
    },
  },
  plugins: [
    require("flowbite/plugin")
  ],
}

