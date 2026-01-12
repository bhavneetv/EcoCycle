importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-app-compat.js');
importScripts('https://www.gstatic.com/firebasejs/9.23.0/firebase-messaging-compat.js');

firebase.initializeApp({
    apiKey: "AIzaSyBtQBM4OOBCrCUvWDq8fmdeuYf7irzsQi0",
    authDomain: "ecocycle-efdec.firebaseapp.com",
    projectId: "ecocycle-efdec",
    storageBucket: "ecocycle-efdec.firebasestorage.app",
    messagingSenderId: "33438843166",
    appId: "1:33438843166:web:7815a8ee66b2663ce0c90f",
    measurementId: "G-VBW821DQ5T"
});

const messaging = firebase.messaging();

// Handle background messages
messaging.onBackgroundMessage(function(payload) {
    console.log('[SW] Background message received', payload);
    self.registration.showNotification(payload.notification.title, {
        body: payload.notification.body,
        icon: payload.notification.icon || '/firebase-logo.png'
    });
});
