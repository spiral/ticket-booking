<template>
  <div class="border shadow-lg pt-5">
    <h3 class="px-5 pb-2">My tickets</h3>
    <p class="px-5 pb-4 lead text-danger" v-if="notExpiredTickets.length === 0">
      There are no purchased or reserved tickets.
    </p>
    <table class="table mb-0 mt-4" v-else>
      <thead class="bg-white text-muted text-uppercase fs-6 fw-light">
      <tr>
        <th scope="col"><small>Movie</small></th>
        <th scope="col"><small>Total cost</small></th>
        <th scope="col"><small>Places</small></th>
        <th scope="col"><small>Reserved At</small></th>
        <th scope="col"><small>Status</small></th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="ticket in notExpiredTickets">
        <th scope="row">
          <NuxtLink :to="`/screening/${ticket.screening.id}`">
            {{ ticket.screening.movie }}
          </NuxtLink>
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
    this.tickets = await this.$api.auth.getReservations()
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
