import Broadcast from './Broadcast/Broadcast';

const host = window.location.host
const protocol = window.location.protocol === 'https:' ? 'wss:' : 'ws:'

window.ws = new Broadcast({
    host: `${protocol}//${host}/ws`,
    connectionTimeout: 3000,
    maxRetries: 10,
});
