<template>
  <div class="bg-light rounded pt-4">
    <h3 class="px-4 pb-4">Schedule</h3>

    <table class="table table-hover">
      <thead class="bg-white">
      <tr>
        <th scope="col">Movie</th>
        <th scope="col">Starts at</th>
        <th scope="col">Duration</th>
        <th scope="col">Auditorium</th>
        <th scope="col">Ticket<br>price</th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="screening in schedule" @click="openScreening(screening.id)">
        <th scope="row">
          {{ screening.movie.title }}
        </th>
        <td>{{ $moment.unix(screening.starts_at).format('Do MMMM, H:mm') }}</td>
        <td>{{ screening.movie.duration }} min</td>
        <td>
          <strong>{{ screening.auditorium }}</strong> <small>({{ screening.total_seats }} seats)</small>
        </td>
        <td>{{ screening.price.currency }}{{ screening.price.amount }}</td>
      </tr>
      </tbody>
    </table>
  </div>
</template>

<script>
export default {
  data() {
    return {
      schedule: []
    }
  },
  async fetch() {
    const response = await this.$axios.$get('/api/cinema/schedule')
    this.schedule = response.data
  },
  methods: {
    openScreening(screening_id) {
      console.log(screening_id)
      this.$router.push(`/screening/${screening_id}`)
    }
  }
}
</script>
