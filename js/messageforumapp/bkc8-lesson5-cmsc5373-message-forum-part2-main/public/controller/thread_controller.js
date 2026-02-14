import { Dev } from "../model/constant.js";
import { Reply } from "../model/reply.js";
import { currentUser } from "./firebase_auth.js";
import { addReply, updateReply } from "./firestore_controller.js";
import { renderReply } from "../view/thread_page.js";
import { deleteReply } from "./firestore_controller.js";
import { progressMessage } from "../view/progress_view.js";
export async function onSubmitAddReply(e){
 e.preventDefault();
 const content = e.target.content.value;
 const uid = currentUser.uid;
 const email = currentUser.email;
 const timestamp =Date.now();
 const threadId = e.submitter.id; // thread owner's doc id
 const threadUid = e.submitter.value;
 const reply = new Reply({
    uid, email,timestamp, content, threadId, threadUid
 });
 
 const progress = document.createElement('div');
 progress.innerHTML = progressMessage('Adding........');
 e.target.prepend(progress);




 try{
     const docId = await addReply(reply);
     reply.set_docId(docId);
 } catch(e){
    if(Dev) console.log('failed to add reply',e);
    alert('failed to add reply: '+JSON.stringify(e));
    return;
 }
progress.remove();


 renderReply(reply);
  e.target.reset();
}

export async function onSubmitEditReply(e,reply){
    e.preventDefault();
    const buttonValue = e.submitter.value;
    const buttons = e.target.querySelectorAll('button');
    const editButton = buttons[0];
    const deleteButton = buttons[1];
    const updateButton = buttons[2];
    const cancelButton = buttons[3];
    const textarea = e.target.querySelector('textarea');
    if (buttonValue =='edit'){
     textarea.disabled = false;
     editButton.classList.replace('d-inline-block','d-none');
     deleteButton.classList.replace('d-inline-block','d-none');
     updateButton.classList.replace('d-none','d-inline-block');
     cancelButton.classList.replace('d-none','d-inline-block');
    } else if (buttonValue =='cancel'){
      textarea.value = reply.content; // restore the originl contetnt
        textarea.disabled = true;
     editButton.classList.replace('d-none','d-inline-block');
     deleteButton.classList.replace('d-none','d-inline-block');
     updateButton.classList.replace('d-inline-block','d-none');
     cancelButton.classList.replace('d-inline-block','d-none');
    }
    else if(buttonValue =='delete'){
      if(!confirm("confirm to delete the reply?")) return;
      const docId = reply.docId;
      const progress = document.createElement('div');
      progress.innerHTML = progressMessage('Deleting....');
      e.target.prepend(progress);
      try{
         await deleteReply(docId);
        // update web browser to remove the reply row
        //<tr><td><form>
        const tr = e.target.parentElement.parentElement;
        tr.remove();
      } catch(e){
         if(Dev) console.log('failed to delete:',e);
         alert('failed to delete reply: '+JSON.stringify(e));
         progress.remove();
      }}
   else if (buttonValue =='update'){
     const docId = reply.docId;
     const newContent = textarea.value;
     const newTimestamp = Date.now();
     const progress = document.createElement('div');
     progress.innerHTML =progressMessage('updating.....');
     e.target.prepend(progress);
     try {
      await updateReply(docId,{
       content: newContent,
       timestamp: newTimestamp,
      });
      const tdEmailTimestamp = e.target.parentElement.parentElement.querySelectorAll('td')[1];
      tdEmailTimestamp.innerHTML =`
      ${reply.email}<br>(${new Date(newTimestamp).toLocaleString()})
      `;
     } catch(e){
      if (Dev) console.log('update error',e);
      textarea.value = reply.content;
      alert('Update error :'+ JSON.stringify(e));
      progress.remove();
      return;

      }
      progress.remove();
      //textarea.value = reply.content; // restore the originl contetnt
        textarea.disabled = true;
     editButton.classList.replace('d-none','d-inline-block');
     deleteButton.classList.replace('d-none','d-inline-block');
     updateButton.classList.replace('d-inline-block','d-none');
     cancelButton.classList.replace('d-inline-block','d-none');
   }
    }
