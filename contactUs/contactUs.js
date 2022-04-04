// window.addEventListener("reload",()=>{
//     window.location = "./contactUs.php";
// })
let alert = document.getElementById("alert");

let form = document.getElementById("contactUsForm");
form.addEventListener("submit",(e)=>{
    e.preventDefault();
    
    let form = document.getElementById("contactUsForm");
    let formData = new FormData(form);
    
    let xhr = new XMLHttpRequest();
    let url = "./contactUsBack.php";
    xhr.open("POST",url,true);
    xhr.onload=()=>{
        let data = xhr.responseText;
        // console.log(data);
        alert.innerHTML = data;
        
    }
    xhr.send(formData);
    form.reset();
})