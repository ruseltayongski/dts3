import Pusher from "pusher-js"; // Import Pusher from 'pusher-js'
import Echo from "laravel-echo";

// Declare the Pusher property on the Window interface
declare global {
    interface Window {
        Pusher: typeof Pusher;
        Echo: Echo;
    }
}

window.Pusher = Pusher; // Assign the imported Pusher to the global object

/*window.Echo = new Echo({
     broadcaster: 'pusher',
     authEndpoint: "/dts/broadcasting/auth",
     key: process.env.MIX_PUSHER_APP_KEY,
     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
     wsHost: window.location.hostname,
//    /!* wssPort: 6001,
//     disableStats: true,
//     enabledTransports: ['ws', 'wss']*!/
     wsPort: 6001,
     forceTLS: true,
     disableStats: true,
 });*/

//  window.Echo = new Echo({
//     broadcaster: 'pusher',
//     authEndpoint: "/dts/broadcasting/auth",
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     wsHost: window.location.hostname,
//     wssPort: 6001,
//     disableStats: true,
//     enabledTransports: ['ws', 'wss'] // <- added this param
// });


// window.Echo = new Echo({
//     broadcaster: 'pusher',
//     authEndpoint: "/dts/broadcasting/auth",
//     key: process.env.MIX_PUSHER_APP_KEY,
//     cluster: process.env.MIX_PUSHER_APP_CLUSTER,
//     wsHost: window.location.hostname,
//     wsPort: 6001,
//     disableStats: true,
//     enabledTransports: ['ws', 'wss'] // <- added this param
// });


import { createApp } from 'vue';
import App from './layout/App.vue';

const app = createApp(App);
app.mount("#layout_app");