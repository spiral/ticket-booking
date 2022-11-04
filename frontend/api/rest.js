// Auth
export const authRegister = ({$axios}) => async (email, password) => $axios.$post('/api/auth/register', {email, password})
  .then((response) => response.data.token)

export const getUserReservations = ({$axios}) => async () => $axios.$get('/api/profile/tickets')
  .then((response) => response.data)

// Cinema
export const getSchedule = ({$axios}) => async () => $axios.$get('/api/cinema/schedule')
  .then((response) => response.data)

export const getScreeningById = ({$axios}) => async (id) => $axios.$get(`/api/cinema/screening/${id}`)
  .then((response) => response.data)

// Tickets

export const ticketsCancel = ({$axios}) => async (reservation_id) => $axios.$post(`/api/tickets/cancel`, {reservation_id})
  .then((response) => response.data)

export const ticketsPurchase = ({$axios}) => async (reservation_id) => $axios.$post(`/api/tickets/purchase`, {reservation_id})
  .then((response) => response.data)

export const ticketsReserve = ({$axios}) => async (screening_id, seat_ids) => $axios.$post(`/api/tickets/reserve`, {
  screening_id,
  reservation_type_id: 1,
  seat_ids
}).then(response => response)
