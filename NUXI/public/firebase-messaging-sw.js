importScripts('https://www.gstatic.com/firebasejs/9.6.7/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.6.7/firebase-messaging-compat.js');

const firebaseConfig = {
    apiKey: "AIzaSyBS_67f9veXrAsSYnFszPPX0dgae6Bvl6o",
    authDomain: "absensi-cde0a.firebaseapp.com",
    projectId: "absensi-cde0a",
    storageBucket: "absensi-cde0a.appspot.com",
    messagingSenderId: "672855281448",
    appId: "1:672855281448:web:001beb135cc2c1d4404a1d"
};
firebase.initializeApp(firebaseConfig);

const messaging = firebase.messaging();

messaging.onBackgroundMessage((payload) => {
    console.log('[firebase-messaging-sw.js] Received background message ', payload);
    // const notificationTitle = payload.notification.title;
    // const notificationOptions = {
    //     body: payload.notification.body,
    //     icon: payload.notification.icon,
    //     data: {
    //         click_action: payload.notification.click_action,
    //         badgeCount: payload.data.badgeCount
    //     }
    // };

    // self.registration.showNotification(notificationTitle, notificationOptions);
});

self.addEventListener('notificationclick', (event) => {
    console.log('[firebase-messaging-sw.js] Notification click received', event);
    const clickAction = event.notification.data.click_action;

    event.notification.close();
    event.waitUntil(
        clients.openWindow(clickAction)
    );
});

self.addEventListener('push', (event) => {
    console.log('[firebase-messaging-sw.js] Push message received', event);
    const payload = event.data.json();
    console.log(payload.data);

    if ('setAppBadge' in navigator) {
        navigator.setAppBadge(payload.data.badgeCount ?? 1).catch((error) => {
            console.error('Failed to set app badge:', error);
        });
    }
});