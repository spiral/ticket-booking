<template>
  <div class="rounded-3 border">
    <div class="d-flex align-items-center">
      <div class="py-5 pl-5">
        <h2 class="mb-4"><small>Movie</small> {{ movie.title }}</h2>
        <div class="d-flex mb-3">
          <span class="badge text-bg-light border mr-3">Duration {{ movie.duration }} min.</span>
          <span class="badge text-bg-light border">Price {{ price.currency }}{{ price.amount }}</span>
        </div>
        <p class="text-muted">{{ movie.description }}</p>
      </div>
      <div class="ml-5">
        <img :src="`https://fakeimg.pl/350x350/?text=${movie.title}`">
      </div>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    movie: Object,
    price: Object
  }
}
</script>
