export default class Channel {
  ws;
  subscription;

  /**
   * The name of the channel.
   */
  name;

  /**
   * The event formatter.
   */
  eventFormatter;

  /**
   * The event callbacks applied to the socket.
   */
  events = {};

  /**
   * User supplied callbacks for events on this channel.
   */
  listeners = {};

  /**
   * Create a new class instance.
   */
  constructor(ws, name) {
    this.ws = ws;
    this.name = name;

    this.subscribe();
  }

  subscribe() {
    let sub = this.ws.centrifuge.getSubscription(this.name)
    if (!sub) {
      sub = this.ws.centrifuge.newSubscription(this.name, {
        data: this.ws.getToken()
      })

      sub.on('subscribing', (context) => {
        this.ws.logger.debug(`subscribing to ${this.name}`, context)
      })

      sub.on('subscribed', (context) => {
        this.ws.logger.debug(`subscribed to ${this.name}`, context)
      })

      sub.on('unsubscribed', (context) => {
        this.ws.logger.debug(`unsubscribed from ${this.name}`, context)
      })

      sub.subscribe()
    }
    this.subscription = sub
  }

  /**
   * Listen for an event on the channel instance.
   */
  listen(event, callback) {
    this.on(event, callback);

    return this;
  }

  /**
   * Register a callback to be called anytime an error occurs.
   */
  error(callback) {
    return this;
  }

  /**
   * Bind the channel's socket to an event and store the callback.
   */
  on(event, callback) {
    if (!callback) {
      throw new Error('Callback should be specified.');
    }

    if (this.listeners[event] === undefined) {
      this.listeners[event] = [];
    }

    if (!this.events[event]) {
      this.events[event] = (context) => {
        this.ws.logger.debug(`publication on ${context.channel}`, context)

        const payload = context.data || {event: 'null'};

        if (payload.event === event && this.listeners[event].length > 0) {
          this.listeners[event].forEach(cb => cb(payload.data));
        }
      };

      this.subscription.on('publication', this.events[event])
    }

    this.listeners[event].push(callback);

    return this;
  }

  unsubscribe() {
    this.ws.centrifuge.removeSubscription(this.subscription)
    this.subscription.removeAllListeners()
    this.listeners = {}
    this.events = {}
  }
}
