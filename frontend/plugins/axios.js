export default function ({$axios, $logger, redirect}) {
  $axios.setBaseURL(process.env.BASE_URL)

  $axios.onRequest(config => {
    $logger.debug(`Making request: ${config.url}`, config)
  })

  $axios.onError(error => {
    $logger.error(error)
    const code = parseInt(error.response && error.response.status)
    if (code === 401) {
      redirect('/400')
    }
  })
}
