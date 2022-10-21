<template>
  <div class="border rounded p-4 bg-white">
    <b-form @submit="onSubmit">
      <b-form-group
        label="Email address:"
      >
        <b-form-input
          v-model="form.email"
          type="email"
          placeholder="Enter email"
          required
        ></b-form-input>
      </b-form-group>
      <b-form-group
        label="Password:"
      >
        <b-form-input
          v-model="form.password"
          type="password"
          placeholder="Enter password"
          required
        ></b-form-input>
      </b-form-group>

      <div class="d-flex align-items-center justify-content-between">
        <b-button type="submit" variant="primary">Sign up</b-button>
        <NuxtLink to="/login">Sign in</NuxtLink>
      </div>
    </b-form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        email: 'admin@site.com',
        password: 'password1',
      }
    }
  },
  methods: {
    async onSubmit(event) {
      event.preventDefault()

      try {
        const response = await this.$axios.$post('/api/auth/register', {email: this.form.email, password: this.form.password})
        this.$auth.setUserToken(response.data.token)
      } catch (err) {
        this.$toast.error(err.response.data.errors[0])
      }
    }
  }
}
</script>
