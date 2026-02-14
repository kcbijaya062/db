
  // Import the functions you need from the SDKs you need
  import { initializeApp } from "https://www.gstatic.com/firebasejs/10.8.0/firebase-app.js";
  // TODO: Add SDKs for Firebase products that you want to use
  // https://firebase.google.com/docs/web/setup#available-libraries

  // Your web app's Firebase configuration
  const firebaseConfig = {
    apiKey: "AIzaSyAoSALM0fS2IY23xvBAwFgeUPYw5EGQogY",
    authDomain: "bkc8-cmsc5373-webapp.firebaseapp.com",
    projectId: "bkc8-cmsc5373-webapp",
    storageBucket: "bkc8-cmsc5373-webapp.appspot.com",
    messagingSenderId: "842098681187",
    appId: "1:842098681187:web:ce2bc59effbbfc27e8df56"
  };

  // Initialize Firebase
  const app = initializeApp(firebaseConfig);
 export { app };