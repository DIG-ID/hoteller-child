/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './header.php',
    './index.php',
    './footer.php',
    './404.php',
    './inc/*.php',
    './page-templates/**/*.php',
    './template-parts/**/*.php',
  ],
  theme: {
    fontFamily: {
      sans: ['Renner'],
      serif: ['larken', 'serif'],
    },
    extend: {
      colors: {
        'blue': '#2795A2',
        'gold': '#906841',
      },
    },
  },
  plugins: [
    //require('@tailwindcss/line-clamp'),
  ],
  important: true,
}
