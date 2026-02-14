import { currentUser } from "../controller/firebase_auth.js";

import { root } from "./elements.js";
import { protectedView } from "./protected_view.js";
import { onClickCreateButton, onSubmitCreateForm, onClickCreateForm2ValidateSharedWith, 
  onChangeImageFile , onSubmitSearchForm, 
  onMouseOutCardView,
  onMouseOverCardView,
  onClickCardView,

} from "../controller/home_controller.js";
import { getPhotoMemoList } from "../controller/firestore_controller.js";

let photoMemoList = null;
export let editOverlayModal;

export function removeFromPhotoMemoList(photoMemo){
const index = photoMemoList.findIndex(p => p.docId == photoMemo.docId);
if (index >= 0){
  photoMemoList.splice(index , 1);
}
}

export function reorderPhotoMemoList(){
  photoMemoList.sort((a,b) =>{
   if(a.timestamp < b.timestamp) return 1;
   else if (a.timestamp > b.timestamp) return -1;
   else return 0;
  });
}

export function prependPhotoMemoList(p){
  photoMemoList.splice(0,0, p);
}


export function resetPhotoMemoList(){
  photoMemoList = null;
}

export async function homePageView() {
  if (!currentUser) {
    root.innerHTML = await protectedView();
    return;

  }
  //root.innerHTML ='<h1>Home page </h1>';

  const response = await fetch('/view/templates/home_page_template.html',
    { cache: 'no-store' });
  const divWrapper = document.createElement('div');
  divWrapper.innerHTML = await response.text();
  divWrapper.classList.add('m-4', 'p-4');


  divWrapper.querySelector('#button-create').onclick = onClickCreateButton;
  divWrapper.querySelector('#form-create').onsubmit = onSubmitCreateForm;
  divWrapper.querySelector('#form-create').onclick = onClickCreateForm2ValidateSharedWith;
  divWrapper.querySelector('#image-file-input').onchange = onChangeImageFile;
  divWrapper.querySelector('#form-search').onsubmit = onSubmitSearchForm;

  let homeRoot = divWrapper.querySelector('#home-root');
  
 const editOverlay = divWrapper.querySelector('#edit-overlay');
 editOverlayModal = new bootstrap.Modal(editOverlay , {backdrop:'static'});

  root.innerHTML = '';
  root.appendChild(divWrapper);
  if (photoMemoList == null) {
    homeRoot.innerHTML ='<h2>loading...</h2>';
    try {
      photoMemoList = await getPhotoMemoList(currentUser.uid);
    }
    catch (e) {
      homeRoot.innerHTML ='';
      console.log('Failed to read: ', e);
      alert('failed to get photomemo list: ' + JSON.stringify(e));
      return;
    }
  }

  if(photoMemoList.length ==0){
    homeRoot.innerHTML ='<h2> No photomemo has been added! </h2>'
    return;
  }
  homeRoot.innerHTML ='';
  photoMemoList.forEach(p => {
    const cardView = createPhotoMemoView(p);
    homeRoot.appendChild(cardView);
  });

}


export function createPhotoMemoView(photoMemo) {
  const cardView = document.createElement('div');
  cardView.classList.add('card', 'd-inline-flex');
  cardView.style = 'width: 18rem;';
  cardView.onmouseover = e =>  onMouseOverCardView(e , cardView);
  cardView.onmouseout = e => onMouseOutCardView(e , cardView);
  cardView.onclick = e => onClickCardView(e, cardView, photoMemo);
  const img = document.createElement('img');
  img.src = photoMemo.imageURL;
  img.classList.add('card-img-top');
  cardView.appendChild(img);

  const cardBody = document.createElement('div');
  cardBody.classList.add('card-body');
  cardView.appendChild(cardBody);
  const h5 = document.createElement('h5');
  h5.innerHTML = photoMemo.title;
  h5.classList.add('card-title');
  cardBody.appendChild(h5);

  const p = document.createElement('p');
  p.classList.add('card-text');
  p.innerHTML = `
  ${photoMemo.memo}<br>
  <hr>
  Created By: ${photoMemo.createdBy}<br>
  created At: ${new Date(photoMemo.timestamp).toLocaleString()}<br>
  SharedWith : ${photoMemo.sharedWith.join('; ')}<br>
  <I>Image Classes: [${Array.isArray(photoMemo.imageClasses) ? photoMemo.imageClasses.join('; ') :'N/A'}]</I>

  `;
  //<I>Image Classes: [${Array.isArray(photoMemo.imageClasses) && photoMemo.imageClasses.length > 0 ? photoMemo.imageClasses.join('; ') : 'N/A'}]</I>

  cardBody.appendChild(p);
  return cardView;
}

export function showSearchResults(results, searchkeys){
  const homeRoot = document.getElementById('home-root');
  homeRoot.innerHTML ='';
 const p = document.createElement('p');
 p.classList.add('fs-3','text-center'); //fs = fontsize =3
 p.innerHTML =`
 search Results By [${searchkeys}]<br>
 ${results.length} matching PhotoMemo(s) found.
 `;
 homeRoot.appendChild(p);
 results.forEach(p =>{
  const cardView = createPhotoMemoView(p);
  homeRoot.appendChild(cardView);
 });
}