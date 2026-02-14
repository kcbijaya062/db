// eq2.js // from paper
const num=document.querySelector('input');
const ul=document.querySelector('ul');
num.onchange=function(e){
    const n1=parseInt(num.value);
    for(let i=1;i<=n1;i++){
        const li = document.createElement('li');
        li.textContent =`${i}`;
        ul.appendChild(li);
    
    }
    document.createElement('br');
}