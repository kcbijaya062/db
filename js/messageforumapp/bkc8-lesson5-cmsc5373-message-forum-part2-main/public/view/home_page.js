import { currentUser } from "../controller/firebase_auth.js";

import { root } from "./elements.js";
import { protectedView } from "./protected_view.js";
import { onClickCreateButton,onSubmitCreatemessage } from "../controller/home_controller.js";
import { getThreadList } from "../controller/firestore_controller.js";
import { Dev } from "../model/constant.js";
import { onClickViewButton } from "../controller/home_controller.js";
import { progressMessage } from "./progress_view.js";

export async function homePageView(){
  if(!currentUser){
  root.innerHTML = await protectedView();
  return;

  }
root.innerHTML = progressMessage('loading .....');



  let threadList;
  try{
    threadList = await getThreadList();

  } catch(e){
    if (Dev) console.log('getThreadList error',e);
    alert('Failed to get threads:'+JSON.stringify(e));
  }




  //root.innerHTML ='<h1>Home page </h1>';

 const response = await fetch('/view/templates/home_page_template.html',
 {cache:'no-store'});
const divWrapper = document.createElement('div');
divWrapper.innerHTML = await response.text();
divWrapper.classList.add('m-4','p-4');

const createButton = divWrapper.querySelector('#create-button');
createButton.onclick = onClickCreateButton;

const form = divWrapper.querySelector('form');
form.onsubmit = onSubmitCreatemessage;

root.innerHTML='';
root.appendChild(divWrapper);
//rendering tbody tag
const tbody = divWrapper.querySelector('tbody');
threadList.forEach(thread =>tbody.appendChild(createMessageRow(thread)));

}
export function prependThread(thread){
  const tr = createMessageRow(thread);
  const tbody = document.querySelector('tbody');
  tbody.prepend(tr);
}


export function createMessageRow(thread){
   const tdAction = document.createElement('td');
   tdAction.innerHTML =`
   <button id="${thread.docId}" class="btn btn-outline-primary">
   View</button>`;
   tdAction.querySelector('button').onclick =onClickViewButton;
  tdAction.innerHTMl ='view';
  const tdTitle = document.createElement('td');
  tdTitle.textContent = thread.title;
  const tdEmail = document.createElement('td');
  tdEmail.textContent = thread.email;
  const tdContent = document.createElement('td');
  tdContent.textContent = thread.content;
  const tdTimestamp = document.createElement('td');
  tdTimestamp.textContent = new Date(thread.timestamp).toLocaleString();

  const tr= document.createElement('tr');
  tr.appendChild(tdAction);
  tr.appendChild(tdTitle);
  tr.appendChild(tdEmail);
  tr.appendChild(tdContent);
  tr.appendChild(tdTimestamp);
  return tr;
}