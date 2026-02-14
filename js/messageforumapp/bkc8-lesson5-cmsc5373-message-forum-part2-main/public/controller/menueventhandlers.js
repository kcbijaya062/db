import { homePageView } from "../view/home_page.js";
import { Menu2PageView } from "../view/menu2_page.js";
import { signOutFirebase } from "./firebase_auth.js";
import { routePathenames } from "./routecontroller.js";

export function onClickHomeMenu(e){
history.pushState(null,null,routePathenames.HOME);
homePageView();


}
export function onClickMenu2Menu(e){
   history.pushState(null, null, routePathenames.MENU2);
    Menu2PageView();
}
export async function onClickSignoutMenu(e){
    await signOutFirebase(e);
}