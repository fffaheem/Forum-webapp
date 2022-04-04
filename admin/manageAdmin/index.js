

function delAdminFunc(e,username){
    
    let confirmed = confirm("Do you really want to Delete this Account, This processed cannot be reversed");
 
    if(confirmed){
        let formData = new FormData();
        formData.append("username",username)
        
        let url = "./indexDel.php";

        let xhr = new XMLHttpRequest();
        xhr.open("POST",url,true);

        xhr.onload = ()=>{
            let data = xhr.responseText;
            if(data == "success"){
                // let elem = e.parentElement.parentElement;
                let elem = e.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement;
                let parentElem = elem.parentElement;
                parentElem.removeChild(elem);
            }else if(data == "successR"){
                window.location.reload();
            }
            // console.log(data);
        }
        
        xhr.send(formData);
    }
    
    
}

function removeAdminFunc(e,username){
    
    let confirmed = confirm("This will remove this user from being an admin");
 
    if(confirmed){
        let formData = new FormData();
        formData.append("username",username)
        
        let url = "./indexRem.php";

        let xhr = new XMLHttpRequest();
        xhr.open("POST",url,true);

        xhr.onload = ()=>{
            let data = xhr.responseText;
            if(data == "success"){
                // let elem = e.parentElement.parentElement;
                let elem = e.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement;
                let parentElem = elem.parentElement;
                parentElem.removeChild(elem);
            }else if(data == "successR"){
                window.location.reload();
            }
            // console.log(data);
        }
        
        xhr.send(formData);
    }
    
    
}

// function delSupAdminFunc(e,username){
//     alert("You cannot delete a Super Admin");
// }


function AssignSuperAdminFunc(e,username){
    let confirmed = confirm(`Assign "${username}" as Super Admin * \nAre you sure? \n*Note : You will be removed from being a Super Admin, Since their can only be one Super Admin`);
    if(confirmed){
        let formData = new FormData();
        formData.append("username",username);

        let url = "./indexSuper.php";

        let xhr = new XMLHttpRequest();
        xhr.open("POST",url,true);
        xhr.onload = ()=>{
            let data = xhr.responseText;
            if(data == "pass"){
                window.location.reload();
            }
            // console.log(data);
            
        }

        xhr.send(formData)
    }
}

let form = document.getElementById("adminSignUpFormOut");
let checkBox = document.getElementById("showPassword");
let signUpBtn = document.getElementById("signUp");

let fullName = document.getElementById("name");
let fullNameSmall = document.getElementById("fullNameSmall");

let username = document.getElementById("username");
let usernameSmall = document.getElementById("usernameSmall");

let email = document.getElementById("email");
let emailSmall = document.getElementById("emailSmall");

let password = document.getElementById("password");
let passwordSmall = document.getElementById("passwordSmall");

let confirmPassword = document.getElementById("confirmPassword");
let confirmPasswordSmall = document.getElementById("confirmPassSmall");
//===================================================================================================================
//===================================================================================================================
//===================================================================================================================

let boolFullNameValid = false;
let boolUsernameValid = false;
let boolEmailValid = false;
let boolPasswordValid = false;


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
    boolFullNameValid = false;
    let regex = /[A-Z]\w+/;
    let fullNameData = fullName.value;

    if(fullNameData == ""){
        fullNameSmall.innerHTML="Name Cannot Empty";
        addInvalid_RemoveSuccess(fullName,fullNameSmall);
    }else{
        if(!regex.test(fullNameData)){       
            fullNameSmall.innerHTML="First letter should be Capital";
            addInvalid_RemoveSuccess(fullName,fullNameSmall);
        }
        else{
            if(fullNameData.length < 3){
                fullNameSmall.innerHTML="Name Cannot be less than 3 character";
                addInvalid_RemoveSuccess(fullName,fullNameSmall);

            }else{       
                fullNameSmall.innerHTML="Good";
                addSuccess_RemoveInvalid(fullName,fullNameSmall);
                boolFullNameValid = true;
            }
            
        }
    }
}


function usernameHandler(){   
    boolUsernameValid = false;

    let usernameData = username.value;
    let firstWord = usernameData[0];
    let lastWord = usernameData[usernameData.length-1];

    let regexStart = /[a-zA-Z_]/;
    let regexMiddle = /^[a-zA-Z0-9_.]+$/
    let regexlast = /^[a-zA-Z0-9_]$/;
    let boolConsecutiveStop = false;

    if(usernameData == ""){
        usernameSmall.innerHTML = "Username cannot be empty";
        addInvalid_RemoveSuccess(username,usernameSmall);
    }
    else if(firstWord == "."){
        usernameSmall.innerHTML = "Username cannot start with a stop";
        addInvalid_RemoveSuccess(username,usernameSmall);
    }
    else if(!regexStart.test(firstWord)){
        usernameSmall.innerHTML = "Username should only begin with alphabet or an underscore";
        addInvalid_RemoveSuccess(username,usernameSmall);
    }
    else if(usernameData.length< 4){
        if(!regexMiddle.test(usernameData)){  
            usernameSmall.innerHTML = "Username can only have alphabet numbers and underscore";
            addInvalid_RemoveSuccess(username,usernameSmall);
        }else{
            // checking for consecutive full Stops
            for (let i = 1; i < usernameData.length; i++) {
                if(usernameData[i]=="." && usernameData[i-1]=="."){
                    boolConsecutiveStop = true;
                }
                
            }
            if(boolConsecutiveStop){
                usernameSmall.innerHTML = "Username cannot have more than one consecutive stop";
                addInvalid_RemoveSuccess(username,usernameSmall);
            }else{                
                usernameSmall.innerHTML = "Username cannot be less than 3 characters";
                addInvalid_RemoveSuccess(username,usernameSmall);
            };
        }
    }
    else{
        if(!regexMiddle.test(usernameData)){  
            usernameSmall.innerHTML = "Username can only have alphabet numbers and underscore";
            addInvalid_RemoveSuccess(username,usernameSmall);

        }else{
            // checking for consecutive full Stops
            for (let i = 1; i < usernameData.length; i++) {
                if(usernameData[i]=="." && usernameData[i-1]=="."){
                    boolConsecutiveStop = true;
                }
                
            }
            if(boolConsecutiveStop){
                usernameSmall.innerHTML = "Username cannot have more than one consecutive stop";
                addInvalid_RemoveSuccess(username,usernameSmall);
            }else{                
                if(lastWord=="."){
                    usernameSmall.innerHTML = "Username cannot end with stop";
                    addInvalid_RemoveSuccess(username,usernameSmall);
                }else if(!regexlast.test(lastWord)){
                    usernameSmall.innerHTML = "Username can Only End with alphabet numbers and underscore";
                    addInvalid_RemoveSuccess(username,usernameSmall);
                }else{                 

                    usernameSmall.innerHTML = 
                    `<div class="spinner-border text-success spinner-border-sm my-3 mx-2" role="status">
                        <span class="sr-only">Loading...</span>
                    </div>`;
                    
                    // checking for existing username 
                    let url = `./indexBack.php?username=${usernameData}`;
            
                    let xhr = new XMLHttpRequest();
        
                    xhr.open("GET",url,true);
                    xhr.onload = ()=>{
                        let data = xhr.responseText;
                        // console.log(data)
                        if(data=="usernameFound"){
                            usernameSmall.innerHTML = "Username already Present";
                            addInvalid_RemoveSuccess(username,usernameSmall);
 
                        }
                        else{
                            usernameSmall.innerHTML = "Good";
                            addSuccess_RemoveInvalid(username,usernameSmall);

                            boolUsernameValid = true;
                        }
                    }
                    xhr.send();      
                             
                }
            }
        }
    }   
}


function emailHandler(){
    boolEmailValid = false;
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

        let url = `./indexBack.php?email=${emailData}`;
        
        let xhr = new XMLHttpRequest();

        xhr.open("GET",url,true);
        xhr.onload = ()=>{
            let data = xhr.responseText;
            // console.log(data);
            
            if(data == "emailFound"){
                emailSmall.innerHTML = "Email is already registered";    
                addInvalid_RemoveSuccess(email,emailSmall);
            }
            else{
                emailSmall.innerHTML = "Good";    
                addSuccess_RemoveInvalid(email,emailSmall);

                boolEmailValid = true;

            }
        }
        xhr.send();    
           
    }
    
}



function passwordHandler(e){

    let elem = e.target;
    let parentElem = elem.parentElement;

    
    let passwordData = password.value;
    let confirmPassData = confirmPassword.value;

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
        if(passwordData == confirmPassData){
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

    let passwordData = password.value;
    let confirmData = confirmPassword.value;

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


fullName.addEventListener("input",fullNameHandler);
username.addEventListener("input",usernameHandler);
email.addEventListener("input",emailHandler);
password.addEventListener("input",passwordHandler);
confirmPassword.addEventListener("input",confirmPasswordHandler);

form.addEventListener("submit",(e)=>{
    e.preventDefault();
    let formData = new FormData(form)
    if(password.value == confirmPassword.value && password.value.length > 5){
        boolPasswordValid = true;
    }else{
        boolPasswordValid = false;
    }
    
    if(boolFullNameValid && boolUsernameValid && boolEmailValid && boolPasswordValid){
        let url = "./indexBack.php";
        let xhr = new XMLHttpRequest();
        xhr.open("POST",url,true);

        xhr.onload = ()=>{
            let data = xhr.responseText;
            console.log(data);
            if(data == "success"){
                window.location.reload();
            }else{
                alert("Something went wrong Please try again later");
            }
            
        }

        xhr.send(formData)
    }

    
})




