
import { homePageView } from "../view/home_page.js";
import { PlayRecordPageView } from "../view/playrecord_page.js";
export const routePathenames={

    HOME:'/',
    PLAYRECORD:'/playrecord',
}

export const routes= [
    {path:routePathenames.HOME, page:homePageView},
    {path: routePathenames.PLAYRECORD, page:PlayRecordPageView}
];

export function routing(pathname,hash){
    const route= routes.find(r=>r.path ==pathname);
    if(route){
        if( hash && hash.length>1){
        route.page(hash.substring(1));
        }
        else{
        route.page();
        }
    }
    else{
       routes[0].page();
       //console.error(`No route found for path: ${pathname}`);
    }
}