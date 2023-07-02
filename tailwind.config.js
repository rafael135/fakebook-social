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
        "180": "48rem",
        "60%": "60%",
        "55%": "55%",
        "50%": "50%",
        "45%": "45%",
        "40%": "40%"
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
      minHeight: {
        "16": "4rem"
      },
      aspectRatio: {
        "16/9": "16 / 9"
      },
      opacity: {
        "85": ".85"
      },
      colors: {
        "custom-cyan": {
          100: "#80C3FF",
          200: "#5293CC",
          300: "#2E6799",
          400: "#144066",
          500: "#051E33"
        }
      }
    },
  },
  plugins: [
    require("flowbite/plugin")
  ],
}

