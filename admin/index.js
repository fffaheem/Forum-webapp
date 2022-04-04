let adminForm = document.getElementById("adminForm");


adminForm.addEventListener("submit",(e)=>{
    e.preventDefault();
    let formData = new FormData(adminForm);

    let url = "./indexBack.php";

    let xhr = new XMLHttpRequest();

    xhr.open("POST",url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;
        if(data=="success"){
            window.location.reload();
        }else{
            window.location.reload();
        }
        // console.log(data);
    }
    
    xhr.send(formData);
})