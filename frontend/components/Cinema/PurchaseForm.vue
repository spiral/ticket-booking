<template>
  <div class="p-4 bg-white border rounded">
    <div class="d-flex flex-column">
      <p class="text mb-1">Card holder</p>
      <input class="form-control mb-3" type="text" placeholder="Name" value="Barry Allen">
    </div>
    <div class="d-flex">
      <div class="d-flex flex-fill flex-column mr-2">
        <p class="text mb-1">Card Number</p>
        <input class="form-control mb-3" type="text" placeholder="1234 5678 435678">
      </div>

      <div class="d-flex flex-column mr-2">
        <p class="text mb-1">Expiry</p>
        <input class="form-control mb-3" type="text" placeholder="MM/YYYY" value="02/24">
      </div>
      <div class="d-flex flex-column">
        <p class="text mb-1">CVV/CVC</p>
        <input class="form-control mb-3 pt-2 " type="password" placeholder="***" value="123">
      </div>
    </div>
    <div class="d-flex justify-content-between">
      <div class="btn btn-primary" @click="purchase">
        Pay {{ currency }}{{ total_price }}
      </div>
      <div class="btn btn-danger" @click="cancel">
        Cancel
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    total_price: Number,
    currency: String,
    reservation_id: String
  },
  methods: {

    async cancel() {
      if (!this.reservation_id) {
        this.$toast.warning('You can not cancel without reservation.')
        return
      }

      const response = await this.$axios.$post(`/api/tickets/cancel`, {
        reservation_id: this.reservation_id
      })

      this.$toast.success('Reservation was canceled.')
      this.$emit('canceled')
    },
    async purchase() {
      if (!this.reservation_id) {
        this.$toast.warning('You can not pay without reservation.')
        return
      }

      const response = await this.$axios.$post(`/api/tickets/purchase`, {
        reservation_id: this.reservation_id
      })

      this.$toast.success('Tickets successful purchased. Tickets were sent on your email address.')
      this.$emit('paid')
    },
  }
}
</script>
