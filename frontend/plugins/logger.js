export class Logger {
  constructor(mode) {
    this.mode = mode
  }

  debug(...content) {
    if (this.mode === 'development') {
      this.__log('success', ...content)
    }
  }

  error(...content) {
    if (this.mode === 'development') {
      this.__log('error', ...content)
    }
  }

  info(...content) {
    if (this.mode === 'development') {
      this.__log('info', ...content)
    }
  }

  __log(type, ...content) {
    switch (type) {
      case 'success':
        console.info(...content)
        break
      case 'info':
        console.info(...content)
        break
      case 'error':
        console.error(...content)
        break
    }
  }
}

const logger = (context, inject) => {
  const logger = new Logger(process.env.NODE_ENV)

  inject('logger', logger)
  context.$logger = logger
};

export default logger
