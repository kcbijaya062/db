// oq2.js
const input = document.querySelector('input');
const tbody = document.querySelector('tbody');
tbody.innerHTML='';

input.onchange=function(e){
const inputs = parseInt(input.value);
for (let i=1;i<=inputs;i++){
    const row= document.createElement('tr');
    const tdata = document.createElement('td');
    tdata.textContent =`${i}`;
    row.appendChild(tdata);
    tbody.appendChild(row);  //appending to the table
}
};