export default defineEventHandler((event) => {
  setHeader(event, 'Cross-Origin-Opener-Policy', 'same-origin-allow-popups');
});