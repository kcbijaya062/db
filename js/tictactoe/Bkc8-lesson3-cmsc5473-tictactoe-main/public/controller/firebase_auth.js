
import { 
    getAuth, signInWithEmailAndPassword , onAuthStateChanged,signOut,
} from "https://www.gstatic.com/firebasejs/10.8.0/firebase-auth.js";

import { app } from "./firebase_core.js";
import { Dev } from "../model/constant.js";
import { signinPageview } from "../view/signin_page.js";
import { routing } from "./routecontroller.js";
 import { game } from "../view/home_page.js";
import { routePathenames } from "./routecontroller.js"; 

 const auth = getAuth(app);
 export let currentUser =null;
 export async function signinFirebase(e){
e.preventDefault();
const email = e.target.email.value;
const password = e.target.password.value;

try{
      const userCredential= await signInWithEmailAndPassword(auth, email, password); 
       //const user= userCredential.user;
} catch(error)
{
    if(Dev)console.log('signin error:',error);
    const errorCode = error.code;
    const errorMessage = error.message;
    alert('signin Error:'+errorCode+''+errorMessage);
}
}
export function attachAuthStateChangeObserver() {
    onAuthStateChanged(auth,authStateChangeListener);

}
function authStateChangeListener(user){
    currentUser =user;
    if(user){
      userInfo.textContent = user.email;

       // console.log('user:',user.email);
      const postAuth = document.getElementsByClassName('myclass-postauth');
      for( let i=0;i<postAuth.length;i++){
        postAuth[i].classList.replace('d-none','d-block');
      }
      const preAuth = document.getElementsByClassName('myclass-preauth');
      for(let i=0;i<preAuth.length;i++){
        preAuth[i].classList.replace('d-block','d-none');
      }
      
      const pathname = window.location.pathname;
      const hash = window.location.hash;
      routing(pathname,hash);
      
      //homePageView();
    }
    else{
      userInfo.textContent = 'No User';

        const postAuth = document.getElementsByClassName('myclass-postauth');
        for(let i=0;i<postAuth.length;i++){
            postAuth[i].classList.replace('d-block','d-none');
        }
        const preAuth = document.getElementsByClassName('myclass-preauth');
      for(let i=0;i<preAuth.length;i++){
        preAuth[i].classList.replace('d-none','d-block');
      }
        history.pushState(null,null,routePathenames.HOME);
      //console.log('signed out');
      game.reset();
       signinPageview();
    }

}

export async function signOutFirebase(){
    await signOut(auth);
}

