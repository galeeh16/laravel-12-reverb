import Echo from 'laravel-echo';

import Pusher from 'pusher-js';
window.Pusher = Pusher;

console.log('echo.js loaded')

window.Echo = new Echo({
    broadcaster: 'reverb',
    key: import.meta.env.VITE_REVERB_APP_KEY,
    wsHost: import.meta.env.VITE_REVERB_HOST,
    wsPort: import.meta.env.VITE_REVERB_PORT ?? 80,
    wssPort: import.meta.env.VITE_REVERB_PORT ?? 443,
    forceTLS: (import.meta.env.VITE_REVERB_SCHEME ?? 'https') === 'https',
    enabledTransports: ['ws', 'wss'],
    authEndpoint: '/api/custom/auth',
    authorizer: (channel, options) => {
        return {
            authorize: (socketId, callback) => {
                axios.post('/api/custom/auth', {
                    socket_id: socketId,
                    channel_name: channel.name
                }, {
                    headers: {
                        // 'Authorization': `Bearer ${your_access_token}` // Contoh: Tambahkan Bearer Token
                    }
                }).then(response => {
                    // console.log(response.data);
                    callback(false, response.data);
                }).catch(error => {
                    // console.log(error);
                    callback(true, error);
                });
            }
        };
    },
});