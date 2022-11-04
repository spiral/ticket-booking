import Centrifuge from 'centrifuge'
import Channel from './Channel'

export class WsClient {
  channels = {}

  constructor(centrifuge, logger, getToken) {
    this.centrifuge = centrifuge
    this.logger = logger
    this.getToken = getToken
  }

  rpc(method, data) {
    if (typeof data !== 'object') {
      data = {}
    }
    data['authToken'] = this.getToken()
    return this.centrifuge.rpc(method, data)
      .then(result => {
        this.logger.debug('rpc result', result)
        return result
      })
  }

  connect() {
    this.centrifuge.on('connecting', (context) => {
      this.logger.debug('connecting to Centrifugo', context)
    })

    this.centrifuge.on('connected', (context) => {
      this.logger.debug('connected to Centrifugo', context)
    })

    this.centrifuge.on('disconnected', (context) => {
      this.logger.debug('disconnected from Centrifugo', context)
    })

    this.centrifuge.connect()

    return this.centrifuge.ready()
  }

  disconnect() {
    Object.entries(this.channels).forEach(([key, channel]) => {
      channel.unsubscribe()
    })
    this.centrifuge.removeAllListeners()
    this.centrifuge.disconnect()
  }

  channel(channel) {
    if (!this.channels[channel]) {
      this.channels[channel] = new Channel(this, channel);
    }

    return this.channels[channel];
  }

  on(channel, event, callback) {
    this.channel(channel).on(event, callback)
  }
}
