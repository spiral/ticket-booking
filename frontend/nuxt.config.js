export default {
  ssr: false,
  head: {
    title: 'Ticket booking system',
    htmlAttrs: {
      lang: 'en'
    },
    meta: [
      {charset: 'utf-8'},
      {name: 'viewport', content: 'width=device-width, initial-scale=1'},
      {hid: 'description', name: 'description', content: ''},
      {name: 'format-detection', content: 'telephone=no'}
    ],
    link: [
      {rel: 'icon', type: 'image/x-icon', href: '/favicon.svg'}
    ]
  },

  css: [],
  plugins: [],
  // Auto import components: https://go.nuxtjs.dev/config-components
  components: true,
  buildModules: [
    '@nuxtjs/moment'
  ],
  modules: [
    '@nuxtjs/axios',
    '@nuxtjs/auth-next',
    // https://go.nuxtjs.dev/bootstrap
    'bootstrap-vue/nuxt',
    '@nuxtjs/toast',
  ],
  axios: {
    baseURL: 'http://127.0.0.1:8080'
    // proxy: true
  },
  toast: {
    position: 'top-center',
    duration: 3000,
    closeOnSwipe: true,
    theme: "bubble"
  },
  auth: {
    strategies: {
      local: {
        token: {
          property: 'data.token',
          global: true,
          name: 'X-Auth-Token',
          required: true,
          type: ''
        },
        user: {
          property: 'data',
          autoFetch: true
        },
        endpoints: {
          login: {url: '/api/auth/login', method: 'post'},
          logout: {url: '/api/auth/logout', method: 'post'},
          user: {url: '/api/auth/profile', method: 'get'}
        }
      },
    }
  },

  build: {}
}
