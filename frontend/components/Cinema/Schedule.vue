<template>
  <div class="border pt-4 shadow-lg">
    <div class="p-5">
      <h3>Movies</h3>
      <p class="lead">Browse the latest movies out now, advanced ticket bookings and movies coming soon.</p>
    </div>
    <table class="table table-hover mb-0">
      <thead class="bg-white text-muted text-uppercase fs-6 fw-light">
      <tr>
        <th scope="col"><small>Movie</small></th>
        <th scope="col"><small>Starts at</small></th>
        <th scope="col"><small>Duration</small></th>
        <th scope="col"><small>Auditorium</small></th>
        <th scope="col"><small>Ticket<br>price</small></th>
      </tr>
      </thead>
      <tbody>
      <tr v-for="screening in schedule" @click="openScreening(screening.id)" class="movie-row">
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
    this.schedule = await this.$api.cinema.getSchedule()
  },
  methods: {
    openScreening(screening_id) {
      this.$router.push(`/screening/${screening_id}`)
    }
  }
}
</script>


<style type="text/css">
.movie-row:hover {
  cursor: pointer;
}
</style>
