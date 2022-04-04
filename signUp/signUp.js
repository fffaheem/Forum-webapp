let checkBox = document.getElementById("showPassword");
let signUpBtn = document.getElementById("signUp");
let form = document.getElementById("signUpForm");
let fullName = document.getElementById("name");
let username = document.getElementById("username");
let email = document.getElementById("email");
let password = document.getElementById("password");
let confirmPassword = document.getElementById("confirmPassword");


let fullNameSmall = document.getElementById("fullNameSmall");
let usernameSmall = document.getElementById("usernameSmall");
let emailSmall = document.getElementById("emailSmall");
let passwordSmall = document.getElementById("passwordSmall");
let confirmPasswordSmall = document.getElementById("confirmPasswordSmall");



let valid = {
    boolNameValid:false,
    boolUsernameValid:false,
    boolEmailValid:false,
    boolpasswordValid:false,
    boolConfirmPasswordValid:false
}


// show Password 
 checkBox.addEventListener("click",()=>{
     if(checkBox.checked){
         password.setAttribute("type","text");
         confirmPassword.setAttribute("type","text");
     }
     else{
         password.setAttribute("type","password") ;
         confirmPassword.setAttribute("type","password") ;

     }
 })
// --------------------------------

// ---------------------
// -------Utility ------
// ---------------------
function addInvalid_RemoveSuccess(elem,elemSmall){
    elem.classList.add("is-invalid")
    elemSmall.classList.add("text-danger","mx-1");

    elem.classList.remove("is-valid")
    elemSmall.classList.remove("text-success","mx-1");
}

function addSuccess_RemoveInvalid(elem,elemSmall){
    elem.classList.remove("is-invalid")
    elemSmall.classList.remove("text-danger","mx-1");

    elem.classList.add("is-valid")
    elemSmall.classList.add("text-success","mx-1");
}

//----------------------------
//----------------------------


function fullNameHandler(){    
    valid.boolNameValid = false;

    let regex = /[A-Z]\w+/;
    let fullNameData = fullName.value;

    if(!regex.test(fullNameData)){  
        addInvalid_RemoveSuccess(fullName,fullNameSmall);
        fullNameSmall.innerHTML = "First letter should be Capital";
    }
    else{
        if(fullNameData.length < 3){
            addInvalid_RemoveSuccess(fullName,fullNameSmall);
            fullNameSmall.innerHTML = "Name Cannot be less than 3 character";
        }else{    
            addSuccess_RemoveInvalid(fullName,fullNameSmall)
            fullNameSmall.innerHTML = "Good";
            valid.boolNameValid = true;
        }
        
    }
}



function usernameHandler(){   
    valid.boolUsernameValid = false;

    let usernameData = username.value;
    let errorMessage = "";
    let firstWord = usernameData[0];
    let lastWord = usernameData[usernameData.length-1];

    let regexStart = /[a-zA-Z_]/;
    let regexMiddle = /^[a-zA-Z0-9_.]+$/
    let regexlast = /^[a-zA-Z0-9_]$/;
    let boolConsecutiveStop = false;

    if(usernameData == ""){
        addInvalid_RemoveSuccess(username,usernameSmall);
        errorMessage = "Username cannot be empty";
        usernameSmall.innerHTML = errorMessage;
    }
    else if(firstWord == "."){
        addInvalid_RemoveSuccess(username,usernameSmall);
        errorMessage ="Username cannot start with a stop";
        usernameSmall.innerHTML = errorMessage;
    }
    else if(!regexStart.test(firstWord)){
        addInvalid_RemoveSuccess(username,usernameSmall);
        errorMessage = "Username should only begin with alphabet or an underscore"
        usernameSmall.innerHTML = errorMessage;

    }
    else if(usernameData.length< 4){
        if(!regexMiddle.test(usernameData)){  
            addInvalid_RemoveSuccess(username,usernameSmall);
            errorMessage = "Username can only have alphabet numbers and underscore";
            usernameSmall.innerHTML = errorMessage;

        }else{
            // checking for consecutive full Stops
            for (let i = 1; i < usernameData.length; i++) {
                if(usernameData[i]=="." && usernameData[i-1]=="."){
                    boolConsecutiveStop = true;
                }
                
            }
            if(boolConsecutiveStop){
                addInvalid_RemoveSuccess(username,usernameSmall);
                errorMessage = "Username cannot have more than one consecutive stop";
                usernameSmall.innerHTML = errorMessage;

            }else{            
                addInvalid_RemoveSuccess(username,usernameSmall);    
                errorMessage = "Username cannot be less than 3 characters";
                usernameSmall.innerHTML = errorMessage;

            };
        }
    }
    else{
        if(!regexMiddle.test(usernameData)){  
                addInvalid_RemoveSuccess(username,usernameSmall);
                errorMessage = "Username can only have alphabet numbers and underscore";
                usernameSmall.innerHTML = errorMessage;

        }else{
            // checking for consecutive full Stops
            for (let i = 1; i < usernameData.length; i++) {
                if(usernameData[i]=="." && usernameData[i-1]=="."){
                    boolConsecutiveStop = true;
                }
                
            }
            if(boolConsecutiveStop){
                addInvalid_RemoveSuccess(username,usernameSmall);
                errorMessage = "Username cannot have more than one consecutive stop";
                usernameSmall.innerHTML = errorMessage;

            }else{                
                if(lastWord=="."){
                    addInvalid_RemoveSuccess(username,usernameSmall);
                    errorMessage = "Username cannot end with stop";
                    usernameSmall.innerHTML = errorMessage;

                }else if(!regexlast.test(lastWord)){
                    addInvalid_RemoveSuccess(username,usernameSmall);
                    errorMessage = "Username can Only End with alphabet numbers and underscore";
                    usernameSmall.innerHTML = errorMessage;

                }else{ 
                    
                    
                    usernameSmall.innerHTML = 
                    `<div class="spinner-border text-success spinner-border-sm my-3 mx-2" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>`;

                    // checking for existing username 
                    let url = `./signUpBack.php?username=${usernameData}`;
            
                    let xhr = new XMLHttpRequest();
        
                    xhr.open("GET",url,true);
                    xhr.onload = ()=>{
                        let data = xhr.responseText;
                        // console.log(data)
                        if(data=="usernameFound"){
                            addInvalid_RemoveSuccess(username,usernameSmall);
                            errorMessage = "Username already present"
                            usernameSmall.innerHTML = errorMessage;
                        }
                        else{
                            // everything is alright 
                            addSuccess_RemoveInvalid(username,usernameSmall);
                            usernameSmall.innerHTML = "Good";
                            valid.boolUsernameValid = true;                           
                        }
                    }
                    xhr.send();                    
                }
            }
        }
    }   
}


function emailHandler(){
    
    valid.boolEmailValid = false;

    let emailData = email.value;
    let regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;

    
    if(!regex.test(emailData)){   
        emailSmall.innerHTML = "Invalid Email";
        addInvalid_RemoveSuccess(email,emailSmall);
    }
    else{
        
        emailSmall.innerHTML = 
        `<div class="spinner-border text-success spinner-border-sm my-3 mx-2" role="status">
            <span class="sr-only">Loading...</span>
        </div>`;
        
        let url = `./signUpBack.php?email=${emailData}`;
        
        let xhr = new XMLHttpRequest();

        xhr.open("GET",url,true);
        xhr.onload = ()=>{
            let data = xhr.responseText;
            // console.log(data)
            if(data=="emailFound"){
                emailSmall.innerHTML = "Email already present";
                addInvalid_RemoveSuccess(email,emailSmall);
            }
            else{
                emailSmall.innerHTML = "Good";
                addSuccess_RemoveInvalid(email,emailSmall);
                valid.boolEmailValid = true;
            }
        }
        xhr.send();        
    }
    
}


function passwordHandler(){

    let passwordData = password.value;

    if(passwordData.length<=6){
        addInvalid_RemoveSuccess(password,passwordSmall);
        passwordSmall.innerHTML = "Password too short! it should be greater than 6 character";


        if(passwordData == confirmPassword.value){
            addSuccess_RemoveInvalid(confirmPassword,confirmPasswordSmall);
            confirmPasswordSmall.innerHTML = "Matched but password too short!";
        }else{
            addInvalid_RemoveSuccess(confirmPassword,confirmPasswordSmall);
            confirmPasswordSmall.innerHTML = "Password doesn't match";
        }
    }
    else{
        if(passwordData == confirmPassword.value){
            addSuccess_RemoveInvalid(password,passwordSmall);
            addSuccess_RemoveInvalid(confirmPassword,confirmPasswordSmall);
            confirmPasswordSmall.innerHTML = "Matched";
            passwordSmall.innerHTML = "Good";
        }
        else{
            addSuccess_RemoveInvalid(password,passwordSmall)
            addInvalid_RemoveSuccess(confirmPassword,confirmPasswordSmall);
            confirmPasswordSmall.innerHTML = "Password doesn't match";
            passwordSmall.innerHTML = "Good";
        }
    }
}


function confirmPasswordHandler(){
    let confirmData = confirmPassword.value;
    let passwordData = password.value;

    if(!(confirmData == passwordData)){
        addInvalid_RemoveSuccess(confirmPassword,confirmPasswordSmall);
        confirmPasswordSmall.innerHTML = "Password doesn't match";
    }
    else{
        addSuccess_RemoveInvalid(confirmPassword,confirmPasswordSmall);
        if(passwordData.length<=6){
            confirmPasswordSmall.innerHTML = "Matched but password too short!";
        }else{
            addSuccess_RemoveInvalid(password,passwordSmall);
            passwordSmall.innerHTML = "Good";
            confirmPasswordSmall.innerHTML = "Matched";
        }
    }
}


function signUpHandler(e){
    valid.boolpasswordValid = false;
    valid.boolConfirmPasswordValid = false;
    let confirmData = confirmPassword.value;
    let passwordData = password.value;

    if(confirmData == passwordData){
        valid.boolpasswordValid = true;
        valid.boolConfirmPasswordValid = true;
    }
    e.preventDefault();       

    if(valid.boolNameValid && valid.boolUsernameValid && valid.boolEmailValid && valid.boolpasswordValid && valid.boolConfirmPasswordValid){

        // -----------------------------------------------------------------
        // --------------------loader--------------------------------
        // -----------------------------------------------------------------
        let boolLoaderAlreadyExist = false;

        let signUpFormOut = document.getElementById("signUpFormOut");
        
        for (let i = 0; i < signUpFormOut.childElementCount; i++) {        
            if(signUpFormOut.children[i].id == "loaderSpinner" ){
                boolLoaderAlreadyExist = true;
            }        
        }
        if(!boolLoaderAlreadyExist){
            let loaderContainer = document.createElement("div");
            loaderContainer.setAttribute("id","loaderSpinner");
            loaderContainer.setAttribute("class","my-4 d-flex justify-content-center")
            signUpFormOut.insertBefore(loaderContainer,form);
            loaderContainer.innerHTML = `<div class='spinner-border text-primary' role='status'> 
                                            <span class='visually-hidden'> Loading... </span>  
                                        </div>`;
        }
        // ========================================================================        


        let formData = new FormData(form);
        let url = "./signUpBack.php";
        let xhr = new XMLHttpRequest();

        xhr.open("POST",url,true);

        xhr.onload=()=>{
            let data = xhr.responseText;
            // console.log(data);
            if(data=="mailSent"){
                window.location = "../signIn/signIn.php";
            }else{                
                alert("unable to proceed please try again later");
                window.location.reload();
            }
            let spinnerContainer = document.getElementById("loaderSpinner");
            signUpFormOut.removeChild(spinnerContainer);

        }

        xhr.send(formData);
        
    }
    
}


fullName.addEventListener("input",fullNameHandler);
username.addEventListener("input",usernameHandler);
email.addEventListener("input",emailHandler);
password.addEventListener("input",passwordHandler);
confirmPassword.addEventListener("input",confirmPasswordHandler);
form.addEventListener("submit",signUpHandler);







