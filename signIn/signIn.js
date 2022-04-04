let checkBox = document.getElementById("showPassword");
let password = document.getElementById("password");
let username = document.getElementById("username");

checkBox.addEventListener("click",()=>{
    if(checkBox.checked){
        password.setAttribute("type","text");
        // password.type == "text"
    }
    else{
        password.setAttribute("type","password") ;

    }
})


// let understoodBtn = document.getElementById("understood");
let understoodBtn = ()=>{
    let xhr = new XMLHttpRequest();
    let url = "?ok=true";
    xhr.open("GET",url,true);

    

    xhr.onload=()=>{
        let data = xhr.responseText;
        // console.log(data);
        
    }

    xhr.send();
    // window.location="?ok=true";

}



let logInForm = document.getElementById("logInForm");
let passwordSmall = document.getElementById("passwordSmall");
let usernameSmall = document.getElementById("usernameSmall");
// let logInBtn = document.getElementById("logInBtn");

logInForm.addEventListener("submit",(e)=>{
    e.preventDefault();

    let formData = new FormData(logInForm);
    let url = "./signInBack.php";
    let xhr = new XMLHttpRequest();
    xhr.open("POST",url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;
        // console.log(data);
        if(data=="invalidUsername"){
            // restore the password 
            passwordSmall.innerHTML="Your password is safe with us.";
            passwordSmall.classList.remove("text-danger");
            password.classList.remove("is-invalid");

            // change the username
            usernameSmall.innerHTML="invalid Username";
            usernameSmall.classList.add("text-danger");
            username.classList.add("is-invalid");

        }
        if(data=="invalidPassword"){
            // restore the username
            usernameSmall.innerHTML="Enter your Username";
            usernameSmall.classList.remove("text-danger");
            username.classList.remove("is-invalid");

            // Change the passwrord 
            passwordSmall.innerHTML = "Invalid Password";
            passwordSmall.classList.add("text-danger");
            password.classList.add("is-invalid");
        }
        if(data=="success"){


            window.location = "../profile/profile.php";

            // // restore the username
            // usernameSmall.innerHTML="Enter your Username";
            // usernameSmall.classList.remove("text-danger");
            // username.classList.remove("is-invalid");

            // // restore the password 
            // passwordSmall.innerHTML="Your password is safe with us.";
            // passwordSmall.classList.remove("text-danger");
            // password.classList.remove("is-invalid");
        }
        
    }

    xhr.send(formData);
})

