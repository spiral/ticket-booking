<template>
  <div class="border p-4">
    <div v-if="$auth.loggedIn">
      <h4 class="card-title mb-4 flex-full">Payment Details</h4>
      <div v-if="!this.reservation_id">
        <button @click="reserve" type="button" class="btn btn-primary">Reserve</button>
      </div>
      <div v-else>
        <CinemaPurchaseForm
            :reservation_id="reservation_id"
            :total_price="total_price"
            :currency="currency"
            @canceled="$emit('canceled')"
            @paid="$emit('paid')"
        />
      </div>
    </div>
    <div v-else>
      <h4 class="card-title mb-4">Total price: {{ currency }}{{ total_price }}</h4>
      <h4>Authorize to purchase tickets</h4>
      <AuthLogin />
    </div>
  </div>
</template>

<script>
export default {
  props: {
    screening_id: Number,
    total_price: Number,
    currency: String,
    seats: Array
  },
  data() {
    return {
      reservation_id: null,
      expires_at: null
    }
  },
  methods: {
    async reserve() {
      if (this.seats.length === 0) {
        this.$toast.warning('You need select at least 1 seat.')
        return
      }

      const response = await this.$api.tickets.reserve(this.screening_id, this.seats.map(seat => seat.id))
      this.$toast.success('Seats reserved.')

      this.reservation_id = response.id
      const now = new Date();

      this.$emit('reserved', this.reservation_id)
      this.expires_at = this.$moment(response.expires_at).diff(now)
    }
  }
}
</script>

<style scoped>

</style>
