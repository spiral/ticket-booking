<template>
  <div v-if="screening" class="bg-light rounded p-4">
    <h3 class="mb-5"><small>Movie</small> {{ screening.movie.title }}</h3>


    <div class="border pb-4 bg-white rounded">
      <h5 class="text-center my-3">Available seats</h5>

      <div class="alert alert-warning border rounded mt-4" role="alert" v-if="isMaxSeatsSelected">
        <small>You can select max 6 seats!</small>
      </div>

      <div v-for="(rowSeats, row) in screening.seats" class="d-flex">
        <div class="text-center my-1" style="width: 50px">{{ row }}</div>
        <div class="d-flex flex-fill mx-5">
          <div v-for="seat in rowSeats" class="flex-fill border m-1 text-white text-center bg-primary"
               :class="{ 'bg-success': isSelected(seat), 'bg-light': seat.reserved }"
               @click="select(seat)">
            <small>{{ seat.number }}</small>
          </div>
        </div>
        <div class="text-center my-1" style="width: 50px">{{ row }}</div>
      </div>
    </div>

    <CinemaReservation v-if="total_price > 0"
                       :screening_id="screening.id"
                       :seats="selected"
                       :total_price="total_price"
                       :currency="screening.price.currency"
                       @reserved="disableSeatingSelection"
                       @paid="refresh"
                       @canceled="refresh"
    />
  </div>
</template>

<script>
export default {
  props: {
    id: Number
  },
  data() {
    return {
      disabled: false,
      screening: false,
      selected: []
    }
  },
  async fetch() {
    try {
      const response = await this.$axios.$get(`/api/cinema/screening/${this.id}`)
      this.screening = response.data
    } catch (e) {
      throw new Error('Page not found')
    }
  },
  methods: {
    refresh() {
      this.selected = []
      this.disabled = false
      this.$nuxt.refresh()
    },
    isSelected(seat) {
      const index = this.selected.indexOf(seat);

      return index >= 0
    },
    select(seat) {
      if (seat.reserved) {
        return
      }

      if (this.disabled) {
        return
      }

      const index = this.selected.indexOf(seat);

      if (index === -1) {
        if (this.isMaxSeatsSelected) {
          return
        }

        this.selected.push(seat)
      } else {
        this.selected.splice(index, 1)
      }
    },
    disableSeatingSelection() {
      this.disabled = true
    }
  },
  computed: {
    isMaxSeatsSelected() {
      return this.selected.length >= 6
    },
    total_price() {
      return this.selected.length * this.screening.price.amount
    }
  }
}
</script>
