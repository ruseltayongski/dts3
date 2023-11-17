// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
//import { getAnalytics } from "firebase/analytics";
import { getDatabase, ref, set, push, onValue, get, child, limitToLast, query } from "firebase/database";
// TODO: Add SDKs for Firebase products that you want to use
// https://firebase.google.com/docs/web/setup#available-libraries

const initFirebase = () => {
    // Your web app's Firebase configuration
    // For Firebase JS SDK v7.20.0 and later, measurementId is optional
    const firebaseConfig = {
        apiKey: "AIzaSyD-NEWmCxaRVxqiu_OHZB0qYaEpTXVaER8",
        authDomain: "dtsweb-f41a2.firebaseapp.com",
        projectId: "dtsweb-f41a2",
        storageBucket: "dtsweb-f41a2.appspot.com",
        messagingSenderId: "810261982679",
        appId: "1:810261982679:web:b1b4fcaf410f364980ef92",
        measurementId: "G-3N9DW418L2"
    };

    // Initialize Firebase
    //const app = initializeApp(firebaseConfig)
    //const analytics = getAnalytics(app);
    const app = initializeApp(firebaseConfig)

    // Initialize Realtime Database and get a reference to the service
    const database = getDatabase(app);

    return database;
}

const insertFirebase = (inserted_data:any) => {
    const db = initFirebase();
    push(ref(db, 'dts'), inserted_data)
    .then((success) => {
        console.log("success")
    // Data saved successfully!
    })
    .catch((error) => {
        console.log(error)
    // The write failed...
    });
}

const readFirebase = () => {
    // const db = initFirebase();
    // const starCountRef = ref(db, 'ridts');
    // onValue(starCountRef, (snapshot) => {
    //     const data = snapshot.val();
    //     console.log(data)
    // });

    const db = initFirebase();
    const starCountRef = query(ref(db, 'dts'), limitToLast(1));
    onValue(starCountRef, (snapshot) => {
        const data = snapshot.val();
        console.log(data)
    });
}

export { insertFirebase, readFirebase }