// Give the service worker access to Firebase Messaging.
// Note that you can only use Firebase Messaging here. Other Firebase libraries
// are not available in the service worker.importScripts('https://www.gstatic.com/firebasejs/7.23.0/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-app.js');
importScripts('https://www.gstatic.com/firebasejs/8.3.2/firebase-messaging.js');
/*
Initialize the Firebase app in the service worker by passing in the messagingSenderId.
*/
firebase.initializeApp({
    apiKey: "AIzaSyDykzd8-vk962Im23P0fXCi6oB8D0Vp2ms",
    authDomain: "notification-94417.firebaseapp.com",
    databaseURL: 'https://project-id.firebaseio.com',
    projectId: "notification-94417",
    storageBucket: "notification-94417.appspot.com",
    messagingSenderId: "119121087610",
    appId: "1:119121087610:web:b172e73a7a128122b6bfc3",
    measurementId: "G-ZFC7QHJFNQ"
});

// Retrieve an instance of Firebase Messaging so that it can handle background
// messages.
const messaging = firebase.messaging();
messaging.setBackgroundMessageHandler(function (payload) {
    console.log("Message received.", payload);
    const title = "Hello world is awesome";
    const options = {
        body: "Your notificaiton message .",
        icon: "/firebase-logo.png",
    };
    return self.registration.showNotification(
        title,
        options,
    );
});