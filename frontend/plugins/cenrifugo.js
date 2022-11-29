import {WsClient} from '~/modules/Websocket/Client'
import {Centrifuge} from 'centrifuge'

const host = window.location.host
const wsProtocol = window.location.protocol === 'https:' ? 'wss' : 'ws'

const WS_URL = process.env.WS_URL || `${wsProtocol}://${host}/connection/websocket`
const API_GATEWAY_URL = process.env.BASE_URL

const getAuthToken = ($auth) => () => $auth.strategy.token.get()

const subscribe = (auth, client) => client.connect()
  .then(ctx => {
    client.channel('public')
    if (auth.loggedIn) {
      client.channel(`user#${auth.user.id}`)
    }
  })

export default async (ctx, inject) => {
  let headers = {}

  const centrifuge = new Centrifuge(`${WS_URL}`, {
    data: {
      authToken: getAuthToken(ctx.$auth)()
    }
  })

  const client = new WsClient(centrifuge, ctx.$logger, getAuthToken(ctx.$auth))
  await subscribe(ctx.$auth.$state, client)

  ctx.$auth.$storage.watchState(
    'loggedIn',
    async (newValue) => {
      client.disconnect()
      await subscribe(ctx.$auth.$state, client)
    }
  )

  inject('ws', client)
  ctx.$ws = client
}

