<template>
  <CinemaScreening :id="id"/>
</template>

<script>
export default {
  validate({params}) {
    // Must be a number
    return /^\d+$/.test(params.id)
  },

  async asyncData({params}) {
    const id = parseInt(params.id)
    return {id}
  }
}
</script>
