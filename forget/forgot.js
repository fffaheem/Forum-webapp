let form =  document.getElementById("newPasswordForm");
const urlParams = new URLSearchParams(window.location.search);
const email = urlParams.get('email');





form.addEventListener("submit",(e)=>{
    e.preventDefault();

    let formData = new FormData(form)
    formData.append("email",email);

    let url = "./forgotBack.php";
    let xhr = new XMLHttpRequest();
    xhr.open("POST",url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;
        if(data == "confirmFail"){
            alert("Confirm Password doesn't matches Try again")
        }else if(data  == "passwordLess"){
            alert("Password cannot be less than 6 character")
        }else if(data == "passwordChangeFail"){
            alert("Password change Fail Please try again later")
        }
        else{
            alert("password has been changed");
            window.location.reload();
            // console.log(data);
        }
    }

    xhr.send(formData)
})