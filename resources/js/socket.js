// 通过 socket.io 客户端进行 WebSocket 通信
import io from 'socket.io-client';

// const socket = io('http://webchats.test', {
//     path: '/ws',
//     transports: ['websocket']
// });
//长连接 这里将 Websocket 建立连接的入口路由调整为了 /socket.io
const socket = io('http://webchats.test', {
    // path: '/socket.io',
    transports: ['websocket'],
});

export default socket;
