/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    './templates/**/*.twig',
    './blocks/**/*.php',
    './src/**/*.js',
  ],
  theme: {
    extend: {
      // Replace these with your brand's design tokens.
      // See the guide for a full example: https://acorncmo.com/wordpress-guide
      colors: {
        brand: {
          dark:    '#1a1a2e',  // primary dark
          medium:  '#16213e',  // secondary
          accent:  '#e94560',  // accent / CTA
          light:   '#f5f5f5',  // backgrounds
          muted:   '#a0a0a0',  // secondary text
        },
      },
      fontFamily: {
        display: ['serif'],
        sans: ['"Inter"', 'system-ui', 'sans-serif'],
      },
      screens: {
        'sm': '480px',
        'md': '768px',
        'lg': '1025px',
        'xl': '1440px',
      },
    },
  },
  plugins: [],
}
