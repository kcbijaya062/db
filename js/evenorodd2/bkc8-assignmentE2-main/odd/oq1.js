// oq1.js
const input = document.querySelector('input');
const divElem = document.querySelector('div');
divElem.onclick =function(e)
{
    const show= document.getElementById('show');
    const inputvalue= parseInt(input.value);
    show.textContent=`Next number is ${inputvalue+1}`;
};