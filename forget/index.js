let form =  document.getElementById("forgetForm");

form.addEventListener("submit",(e)=>{
    e.preventDefault();

    let loader = `<div id = 'loaderSpinner' class = 'my-4 d-flex justify-content-center'> 
        <div class='spinner-border text-primary' role='status'> 
            <span class='visually-hidden'> Loading... </span>  
        </div>
    </div>`;
    let loaderContainerDiv = document.getElementById("loaderContainerDiv");
    loaderContainerDiv.innerHTML = loader;
    

    let formData = new FormData(form)
    let url = "./indexBack.php";
    let xhr = new XMLHttpRequest();
    xhr.open("POST",url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;

        if(data == "emailNotFound"){
            alert("Email Not Found")
            loaderContainerDiv.innerHTML = "";
            
        }else if(data == "mainSentFail"){
            alert("Please Try again Later")
            loaderContainerDiv.innerHTML = "";
        }else{
            alert("Please check your email")
            loaderContainerDiv.innerHTML = "";

            setTimeout(() => {
                window.location = "../signIn/signIn.php";
            }, 1000);
        }
        // console.log(data);
        
    }

    xhr.send(formData)
})