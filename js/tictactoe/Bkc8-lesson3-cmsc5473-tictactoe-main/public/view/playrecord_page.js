import { root } from "./elements.js";
import { currentUser } from "../controller/firebase_auth.js";
import { protectedView } from "./protected_view.js";
import { Dev } from "../model/constant.js"; import { getAllPlayRecords } from "../controller/firestore_controller.js";
export async function PlayRecordPageView() {

    if (!currentUser) {
        if (!currentUser) {
            root.innerHTML = await protectedView();
            return;
        }
    }

    const response = await fetch('/view/templates/playrecord_page_template.html',
        { cache: 'no-store' });
    const divWrapper = document.createElement('div');
    divWrapper.innerHTML = await response.text();
    divWrapper.classList.add('m-4', 'p-4');
    
    root.innerHTML = '';
    root.appendChild(divWrapper);
    
    
    
    let playRecords;
    try {
        playRecords = await getAllPlayRecords(currentUser.email);

    } catch (e) {
        if (Dev) console.log('failed to getAllPlayRecord', e);
        alert(`failed to get play records: ${JSON.stringify(e)}`);
          return;
    }
    const tbody= divWrapper.querySelector('tbody');
    if (playRecords.length ==0){
        tbody.innerHTML=`
        <tr>
        <td colspan="3" class="text-center fs-3"> No play records found !</td>
        </tr>
        `
    }else{
    playRecords.forEach(record =>tbody.appendChild(buildOnePlayRecordView(record)));
    }



}

function buildOnePlayRecordView(record){
    const tr = document.createElement('tr');
    
    tr.innerHTML =`
    <td>
    ${record.winner}
    </td>
    <td>
    ${record.moves}
    </td>
    <td>
    ${ new Date(record.timestamp).toLocaleString()}
    </td>

    `
    return tr;
}
