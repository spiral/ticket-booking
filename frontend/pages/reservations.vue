<template>
  <div class="bg-light rounded pt-4">
    <h3 class="px-4 pb-4">Tickets</h3>
    <table class="table">
      <thead class="bg-white">
      <tr>
        <th scope="col">Movie</th>
        <th scope="col">Total cost</th>
        <th scope="col">Places</th>
        <th scope="col">Reserved At</th>
        <th scope="col">Status</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="ticket in notExpiredTickets">
        <th scope="row">
          {{ ticket.screening.movie }}
        </th>
        <td>
          {{ ticket.total_cost.currency }}{{ ticket.total_cost.amount }}
        </td>
        <td>
          <ul class="list-group">
            <li class="list-group-item" v-for="(seat, i) in ticket.seats">
              Row: {{ seat.row }}
              Place: {{ seat.number }}
            </li>
          </ul>

        </td>
        <td>{{ $moment.unix(ticket.created_at).fromNow() }}</td>
        <td v-if="ticket.paid_at">
          <span class="text-success">Paid: {{ $moment.unix(ticket.paid_at).fromNow() }}</span>
        </td>
        <td v-else-if="!ticket.canceled_at">
          <div v-if="$moment.unix(ticket.expires_at).isAfter()">
            <span class="text-info">Reserved expires at {{ $moment.unix(ticket.expires_at).fromNow() }}</span>

            <div class="mt-3 d-flex justify-content-between">
              <button class="btn btn-sm btn-primary" @click="purchase(ticket)">
                Process
              </button>
            </div>
          </div>
          <div v-else>Expired</div>
        </td>
        <td v-else>
          <span class="text-danger">Canceled: {{ $moment.unix(ticket.canceled_at).fromNow() }}</span>
        </td>
      </tr>
      </tbody>
    </table>
    <div class="p-4" v-if="reservation">
      <CinemaPurchaseForm
        :reservation_id="reservation.uuid"
        :total_price="reservation.total_cost.amount"
        :currency="reservation.total_cost.currency"
        @canceled="refresh"
        @paid="refresh"
      />
    </div>
  </div>
</template>

<script>
export default {
  middleware: ['auth'],
  data() {
    return {
      reservation: null,
      tickets: []
    }
  },
  async fetch() {
    const response = await this.$axios.$get('/api/profile/tickets')
    this.tickets = response.data
  },
  methods: {
    refresh() {
      this.reservation = null
      this.$nuxt.refresh()
    },
    async purchase(ticket) {
      this.reservation = ticket
    }
  },
  computed: {
    notExpiredTickets() {
      return this.tickets.filter(
        ticket => !ticket.paid_ad && this.$moment.unix(ticket.expires_at)
          .isAfter()
      )
    }
  }
}
</script>
