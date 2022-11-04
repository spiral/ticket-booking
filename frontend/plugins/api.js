import {
  authRegister,
  getUserReservations,
  getSchedule,
  getScreeningById,
  ticketsReserve,
  ticketsCancel,
  ticketsPurchase,
} from '~/api/ws'

export default function (ctx, inject) {
  const api = new class Api {
    get auth() {
      return {
        register: authRegister(ctx),
        getReservations: getUserReservations(ctx)
      }
    }

    get cinema() {
      return {
        getSchedule: getSchedule(ctx),
        getScreeningById: getScreeningById(ctx)
      }
    }

    get tickets() {
      return {
        reserve: ticketsReserve(ctx),
        cancel: ticketsCancel(ctx),
        purchase: ticketsPurchase(ctx)
      }
    }
  }

  inject('api', api)
  ctx.$api = api
}
