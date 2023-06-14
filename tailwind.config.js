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
        "156": "40rem",
        "168": "44rem",
        "180": "48rem"
      },
      maxWidth: {
        "128": "32rem",
        "144": "36rem",
        "156": "40rem",
        "168": "44rem",
        "180": "48rem"
      },
      height: {
        "128": "32rem",
        "144": "36rem",
        "156": "40rem",
        "168": "44rem",
        "180": "48rem"
      },
      maxHeight: {
        "180": "48rem"
      },
      aspectRatio: {
        "16/9": "16 / 9"
      },
      opacity: {
        "85": ".85"
      }
    },
  },
  plugins: [
    require("flowbite/plugin")
  ],
}

