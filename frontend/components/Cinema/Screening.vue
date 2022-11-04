<template>
  <div v-if="screening" class="shadow-lg">
    <CinemaMovie
      :movie="screening.movie"
      :price="screening.price"
    />
    <div>
      <div class="border bg-white py-5 px-3">
        <h5 class="text-center mb-3">Available seats</h5>
        <div class="alert alert-warning border rounded mt-4" role="alert" v-if="isMaxSeatsSelected">
          <small>You can select max 6 seats!</small>
        </div>
        <div v-for="(rowSeats, row) in screening.seats" class="seats-container d-flex"
             :class="{'disabled': disabled}"
        >
          <div class="text-center my-1 text-muted" style="width: 50px">{{ row }}</div>
          <div class="d-flex flex-fill mx-3 seats-row">
            <div v-for="seat in rowSeats"
                 class="d-flex align-items-center justify-content-center seat-item flex-fill border text-white bg-primary"
                 :class="{ 'bg-success': isSelected(seat), 'bg-light': isReserved(seat) }"
                 @click="select(seat)">
              <small>{{ seat.number }}</small>
            </div>
          </div>
          <div class="text-center my-1 text-muted" style="width: 50px">{{ row }}</div>
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
      selected: [],
      reserved: []
    }
  },
  async fetch() {
    try {
      this.screening = await this.$api.cinema.getScreeningById(this.id)
    } catch (e) {
      throw new Error('Page not found')
    }

    Object.entries(this.screening.seats).forEach(([row, rowSeats]) => {
      rowSeats.forEach(seat => {
        if (seat.reserved) {
          this.reserved.push(seat.id)
        }
      })
    })

    this.$ws.channel('screening.' + this.screening.id)
      .listen('cinema.tickets.reserved', (data) => {
        // A customer has reserved seats
        if (this.disabled) return

        data.seats.forEach(id => {
          this.reserved.push(id)
        })

        this.selected = this.selected.filter(seat => data.seats.indexOf(seat.id) !== -1)
      })
      .listen('cinema.reservation.canceled', (data) => {
        this.reserved = this.reserved.filter(id => data.seats.indexOf(id) === -1)
      })
  },
  beforeDestroy() {
    this.$ws.channel('screening.' + this.screening.id).unsubscribe()
  },
  methods: {
    refresh() {
      this.selected = []
      this.disabled = false
      this.$nuxt.refresh()
    },
    isSelected(seat) {
      return this.selected.indexOf(seat) >= 0
    },
    isReserved(seat) {
      return this.reserved.indexOf(seat.id) >= 0
    },
    select(seat) {
      if (this.isReserved(seat)) {
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

<style type="text/css">
.seats-container {

}

.seats-row {

}

.seat-item {
  font-size: 70%;
  min-width: 30px;
}

.seat-item:hover {
  cursor: pointer;
}

.seats-container.disabled .seat-item,
.seat-item.bg-light {
  cursor: not-allowed;
}
</style>
