import { initializeApp } from "firebase/app";
import { getMessaging, getToken, onMessage } from "firebase/messaging";

const firebaseConfig = {
    apiKey: "AIzaSyBS_67f9veXrAsSYnFszPPX0dgae6Bvl6o",
    authDomain: "absensi-cde0a.firebaseapp.com",
    projectId: "absensi-cde0a",
    storageBucket: "absensi-cde0a.appspot.com",
    messagingSenderId: "672855281448",
    appId: "1:672855281448:web:001beb135cc2c1d4404a1d"
  };

// Initialize Firebase
export const firebaseApp = initializeApp(firebaseConfig);

// Initialize Firebase Cloud Messaging
export const messaging = getMessaging(firebaseApp);

// Function to get FCM token
export const getTokens = () => {
    return new Promise((resolve, reject) => {
        getToken(messaging, {
            vapidKey: "BN1bF0kRzwRFpv9_PG-ePQqNmsm3Je8sZ7BW5NKybRwDPwJJyviuBTkKgFCGKjEDb9fmDIRxAHgpAnDBz95uVb4",
        })
            .then((currentToken) => {
                if (currentToken) {
                    console.log("Got FCM registration token:", currentToken);
                    resolve(currentToken);
                } else {
                    console.log("No registration token available. Request permission to generate one.");
                    resolve(false);
                }
            })
            .catch((err) => {
                console.error("An error occurred while retrieving token: ", err);
                resolve(false);
            });
    });
};

// Log incoming messages
onMessage(messaging, (payload) => {
    console.log("Message received. ", payload);
    // Customize how you handle the message here, if necessary
});
