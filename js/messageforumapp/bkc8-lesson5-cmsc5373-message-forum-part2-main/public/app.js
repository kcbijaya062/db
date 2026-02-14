import { attachAuthStateChangeObserver } from "./controller/firebase_auth.js";
import { onClickHomeMenu , onClickMenu2Menu, onClickSignoutMenu } from"./controller/menueventhandlers.js"; 
//import { signinPageview } from "./view/signin_page.js";
import { routing } from "./controller/routecontroller.js";

//menu buttons handlers

document.getElementById('menu-home').onclick = onClickHomeMenu;
document.getElementById('menu-menu2').onclick =onClickMenu2Menu;
document.getElementById('menu-signout').onclick = onClickSignoutMenu;
attachAuthStateChangeObserver();
//signinPageview();
window.onload = function(e){
    const pathname = window.location.pathname;
    const hash = window.location.hash;
    console.log(pathname,hash);  
   routing(pathname,hash);

}
window.onpopstate = function(e){
    e.preventDefault();
    const pathname = window.location.pathname;
    const hash = window.location.hash;
    routing(pathname, hash);
}



//console.log("from app.js")