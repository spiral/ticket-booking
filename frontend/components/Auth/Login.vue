<template>
  <div class="border p-5 bg-white">
    <b-form @submit="onSubmit">
      <b-form-group label="Email address:">
        <b-form-input
          v-model="form.email"
          type="email"
          placeholder="Enter email"
          required
        ></b-form-input>
      </b-form-group>
      <b-form-group label="Password:">
        <b-form-input
          v-model="form.password"
          type="password"
          placeholder="Enter password"
          required
        ></b-form-input>
      </b-form-group>

      <div class="d-flex align-items-center justify-content-between">
        <b-button type="submit" variant="primary">Sign in</b-button>
        <NuxtLink to="/register">Sign up</NuxtLink>
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
        await this.$auth.login({data: {email: this.form.email, password: this.form.password}})
        this.$toast.success('Logged In!')
      } catch (err) {
        this.$toast.error(err.response.data.errors[0])
      }
    }
  }
}
</script>
