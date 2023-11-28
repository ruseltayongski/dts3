// Import the functions you need from the SDKs you need
import { initializeApp } from "firebase/app";
//import { getAnalytics } from "firebase/analytics";
import { getDatabase, ref, set, push, onValue, get, child, limitToLast, query, remove } from "firebase/database";
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
    const dbRef = ref(db, 'dts');
    push(dbRef, inserted_data)
    .then((pushedDataRef) => {
        if (pushedDataRef.key !== null) {
            console.log('Data pushed successfully:', pushedDataRef.key);
            const dataToRemoveRef = child(dbRef, pushedDataRef.key);
            remove(dataToRemoveRef);
        } else {
            console.error('Error: Unable to get key after push.');
            Promise.reject('Unable to get key after push.');
        }
    })
    .then(() => {
        console.log('Data removed successfully after push.');
    })
    .catch((error) => {
        console.error('Error:', error);
    });
}

const readFirebase = (current_user_section_id:Number) => {
    const db = initFirebase();
    const starCountRef = query(ref(db, 'dts'), limitToLast(1));
    onValue(starCountRef, (snapshot) => {
        const data = snapshot.val();
        if(data) {
            const itemValue : any = Object.values(data)[0];
            console.log(itemValue);
            // console.log(current_user_section_id);
            if(itemValue.section_owner_id == current_user_section_id && itemValue.section_accepted_id != current_user_section_id  && itemValue.status == "accepted") {
                Lobibox.notify('success', {
                    title: itemValue.route_no+" was accepted by "+itemValue.user_accepted_name+" / "+itemValue.section_accepted_name,
                    size: 'normal',
                    delay: 30000,
                    closeOnClick: false,
                    img: $("#public_url").val()+"public/img/doh-logo.png",
                    msg: itemValue.remarks
                });
            } 
            else if(itemValue.section_released_to_id == current_user_section_id && itemValue.status == "released") {
                Lobibox.notify('info', {
                    title: itemValue.route_no+" was released by "+itemValue.user_released_name+" / "+itemValue.section_released_by_name,
                    size: 'normal',
                    delay: 30000,
                    closeOnClick: false,
                    img: $("#public_url").val()+"public/img/doh-logo.png",
                    msg: itemValue.remarks
                });
            }
        }
    });
}

const testData = (test:String) => {
    console.log(test);
}

export { insertFirebase, readFirebase, testData }