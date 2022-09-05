/** @type {import('tailwindcss').Config} */
module.exports = {
  content: ["./templates/**/*.html.twig",
  "./assets/**/*.{scss, css}"],
  theme: {
    extend: {},
  },
  plugins: {
    tailwindcss: {},
    autoprefixer: {},
  },
}
