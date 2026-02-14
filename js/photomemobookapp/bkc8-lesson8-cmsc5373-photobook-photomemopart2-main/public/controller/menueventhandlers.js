import { homePageView } from "../view/home_page.js";
import { signOutFirebase } from "./firebase_auth.js";
import { routePathenames } from "./route_controller.js";
import { SharedWithPageView } from "../view/sharedwith_page.js";
export function onClickHomeMenu(e){
history.pushState(null,null,routePathenames.HOME);
homePageView();


}
export function onClickSharedWithMenu(e){
   history.pushState(null, null, routePathenames.SHAREDWITH);
    SharedWithPageView();
}
export async function onClickSignoutMenu(e){
    await signOutFirebase(e);
}