const loader = document.createElement('div');
const img = document.createElement('img');
const body = document.getElementsByTagName("BODY")[0];
const baseUrl = document.querySelector('.baseUrl').value;

console.log('BaseUrl is');
console.log(baseUrl);

loader.classList.add('loader');
img.src = `${baseUrl}images/Book.gif`;

loader.appendChild(img);

body.appendChild(loader);


window.addEventListener("load", vanish);

function vanish() {
  loader.classList.add('disappear');
 }
