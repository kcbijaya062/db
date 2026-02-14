// eq1.js
// eq1.js like my answer
const input = document.querySelector('input');
const para = document.querySelector('p');
para.onclick=function(e){
    const divs = document.getElementById('result');
    const inputvalue= parseInt(input.value);
    divs.textContent=`${inputvalue}x${inputvalue}=${inputvalue*inputvalue}`;
};