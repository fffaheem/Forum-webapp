let descForm = document.getElementById("descForm")
let todayDate = new Date();
let todayDay = todayDate.getDate();
let todayMonth = todayDate.getMonth()+1;
let todayYear = todayDate.getFullYear();
if(todayDay < 10){
    todayDay = `0${todayDay}`;
}
if(todayMonth < 10){
    todayMonth = `0${todayMonth}`;
}
let maxDate = `${todayYear-1}-${todayMonth}-${todayDay}`;
let minDate = `${todayYear-100}-01-01`;


function cancelChange(){
    window.location.reload()
}

function editFunc(){
    let fullnameF = document.getElementById("fullnameF");
    let descF = document.getElementById("descF");
    let dobF = document.getElementById("dobF");
    let ageF = document.getElementById("ageF");
    fullnameF = fullnameF.innerText;
    descF = descF.innerText;
    dobF = dobF.className;
    ageF = ageF.innerText;

  

    if(fullnameF  == "" || fullnameF  == "NA"){
        fullnameF = "";
    }
    if(descF == "" || descF  == "NA"){
        descF = "";
    }
    if(dobF == "" || dobF  == "NA"){
        descF = "";
    }
    if(ageF == "" || ageF  == "NA"){
        ageF = "";
    }


    let form = 
    `<form id='profileForm' onSubmit='profileSubmitFunc(event)'>
        <div class='mb-3'>
            <label for='name' class='form-label'>Name</label>
            <input type='text' class='form-control' id='name' name='fullname' value='${fullnameF}'>
        </div>
        <div class='mb-3'>
            <label for='age' class='form-label'>Age</label>
            <input type='number' class='form-control' onChange='ageChange()' id='age' value='${ageF}'>
        </div>
        <div class='mb-3'>
            <label for='dob' class='form-label'>D.O.B</label>
            <input type='date' class='form-control' onChange='dobChange()' name='dob' id='dob' min='${minDate}' max='${maxDate}' value='${dobF}' >
        </div>
        <div class='mb-3'>
            <label for='desc'>Description</label>
            <textarea class='form-control' placeholder='About you' id='desc' name='desc' style='height: 100px' >${descF}</textarea>
        </div>
        <div class='flexCenter my-5'> 
            <button type='submit' class='btn btn-success mx-3'>Save Changes</button>
            <div class='btn btn-danger mx-3' onClick ='cancelChange()'>Cancel Changes</div>
        </div>
    </form>`;

    let editContainer = document.getElementById("editContainer");
    editContainer.innerHTML = "";
    descForm.innerHTML = form;

   
    
}


function dobChange(){
    let dob = document.getElementById("dob");
    let age = document.getElementById("age");

    let inputDate = new Date(dob.value);
    let inputYear = inputDate.getFullYear();
    let inputMonth = inputDate.getMonth()+1;
    let inputDay = inputDate.getDate();

    if(inputMonth < 10){
        inputMonth = `0${inputMonth}`;
    }
    if(inputDay < 10){
        inputDay = `0${inputDay}`;
    }

    let newYear = '';
    let newMonth = '';
    let newDay = '';
    if(inputYear >= todayYear){
        newYear = todayYear-1;

        if(inputMonth >= todayMonth){
            newMonth = todayMonth;

            if(inputDay >= todayDay){
                newDay = todayDay;
            }
        }

        dob.value = `${newYear}-${newMonth}-${newDay}`;
    }
    else if(inputYear < todayYear-100){
        newYear = todayYear-100;
        newMonth = inputMonth;
        newDay = inputDay;

        dob.value = `${newYear}-${newMonth}-${newDay}`;
    }
    else{
        newYear = inputYear;
        newMonth = inputMonth;
        newDay = inputDay;
    }


    let equation = `${todayYear}.${todayMonth}${todayDay} - ${newYear}.${newMonth}${newDay}`;
    let ageVal = eval(equation);
    ageVal = Math.floor(ageVal);

    age.value = ageVal;
    // console.log(ageVal)

    
}

function ageChange(){
    // min='1' max='100'
    let dob = document.getElementById("dob");
    let age = document.getElementById("age");

    let userYear = '';
    let userMonth = '01';
    let userDay = '01';
    if(age.value < 1){
        age.value = 1;
    }
    if(age.value > 100){
        age.value = 100;
    }

    if(dob.value == "" || dob.value == null){
        userYear = todayYear-age.value;
        dob.value = `${userYear}-${userMonth}-${userDay}`;
    }else{
        let dobDetail = dob.value;
        dobDetail = new Date(dobDetail);
        let dobYear = dobDetail.getFullYear();
        let dobMonth = dobDetail.getMonth()+1;
        let dobDay = dobDetail.getDate();
        if(dobMonth < 10){
            dobMonth = `0${dobMonth}`;
        }
        if(dobDay < 10){
            dobDay = `0${dobDay}`;
        }

        let userYearEqn = `${todayYear}.${todayMonth}${todayDay} - ${age.value}.${dobMonth}${dobDay}`
        userYear = eval(userYearEqn);
        userYear = Math.floor(userYear);   
        
        // console.log(userYearEqn);     

        
        dob.value = `${userYear}-${dobMonth}-${dobDay}`;

    }
    // console.log(age.value,dob.value);
    

 
    
}

function profileSubmitFunc(e){
    e.preventDefault();


    let profileForm = document.getElementById("profileForm");
    let name = document.getElementById("name");
    let dob = document.getElementById("dob");
    let age = document.getElementById("age");

    let boolNameEmpty = false;
    let boolDOBEmpty = false;
    let boolAgeEmpty = false;

    if(name.value == "" || name.value == null){
        boolNameEmpty = true;
    }
    
    if(dob.value == "" || dob.value == null){
        boolDOBEmpty = true;

    }
    if(age.value == "" || age.value == null){
        boolAgeEmpty = true;

    }

    if(!(boolNameEmpty || boolAgeEmpty || boolDOBEmpty)){

        let formData = new FormData(profileForm);

        let url = "./profileBack.php";

        let xhr = new XMLHttpRequest();

        xhr.open("POST",url,true);

        xhr.onload = () =>{
            let data = xhr.responseText;
            // console.log(data);

            if(data=="success"){
                window.location.reload()
            }else{
                alert("failed to update");
                
                setTimeout(() => {
                    window.location.reload()
                    
                }, 1000);
            }
            
        }

        xhr.send(formData)
    }
    
}


let changePicBtn = document.getElementById("changePic");
let picContainer = document.getElementById("picContainer")


function changePic(){
    let newBtn = `
    <div class='d-flex flex-column'>
        <div>
            <button class="btn btn-danger mt-4 me-3" onClick='delDpFunc()'>Delete</button>
            <form id='fileForm' class='d-flex flex-column justify-content-center align-items-center' onSubmit='dpFormSubmit(event)'>
                <label for="uploadDp" class='btn btn-success mt-4 me-3'>Upload DP</label>
                <input type="file" name="uploadDp" id="uploadDp" onChange='dpUploadFunc()' accept=".jpg, .png, .jpeg">
                <div id='fileFormBtn' class = 'mt-3' style='display:none'> 
                </div>
            </form>
        </div>
        <div>
            <button class="btn btn-warning mt-4 me-3" onClick='cancelFunc()'>Cancel</button> 
        </div>
    </div>
    `;
    picContainer.innerHTML=newBtn;
    
}


function cancelFunc(){
    picContainer.innerHTML = `<button class="btn btn-primary mt-4" onClick='changePic()'>Change Profile Pic</button>`;
}


function delDpFunc(){
    let url = "./profileBack.php?delDp=true";

    let xhr = new XMLHttpRequest();

    xhr.open("GET",url,true);

    xhr.onload=()=>{
        let data = xhr.responseText;
        // console.log(data);
        if(data == "success"){
            let userDp = document.getElementById("userDp");
            userDp.src = "../images/noDp.jpg";

            picContainer.innerHTML = `<button class="btn btn-primary mt-4" onClick='changePic()'>Change Profile Pic</button>`;

        }else{
            alert("some Error");
        }
        
    }


    xhr.send();
}


function dpUploadFunc(){
    let fileFormBtn = document.getElementById("fileFormBtn");
    fileFormBtn.innerHTML = "<button type = 'submit' class='btn btn-primary me-3'> Change </button>";
    fileFormBtn.style.display = "block";
    // console.log(`Hey`);
        
}

function dpFormSubmit(e){
    e.preventDefault();
    let fileForm = document.getElementById("fileForm");
    let formData = new FormData(fileForm);

    // console.log(fileForm);
    

    let url = "./profileBackDp.php";

    let xhr = new XMLHttpRequest();

    xhr.open("POST",url,true)

    xhr.onload = ()=>{
        let data = xhr.responseText;
        // console.log(data);
        if(data=="success"){
            window.location.reload()
        }
        
    }

    xhr.send(formData);
}

function delIdFunc(){

    //Checking if user is Admin

    let userEmail = document.getElementById("userEmail").innerText;
    let formData = new FormData();
    formData.append("userEmail",userEmail);

    let xhr = new XMLHttpRequest();
    let url = "./profileCheckAdmin.php";
    xhr.open("POST",url,true);
    xhr.onload = ()=>{
        let data = xhr.responseText;
        if(data =="yes"){
            alert("You are admin, you cannot delete your account from here. Delete it from the admin panel");
        }else{
            let confirmDel = confirm("Do You really want to delete your Account, Your Account cannot be restored later");
            if(confirmDel){
    
                let myHtml = document.querySelector("html");
                myHtml.style.setProperty("--modalbackDisplay","block");
                let myBody = document.querySelector("body");
            
                let navBar = document.querySelector("nav");
                let modal = document.createElement("div");
                modal.setAttribute("class","container");
                modal.setAttribute("id","modalOut");
            
                modal.innerHTML = 
                    `
                    <div class='contaier m-3' id='modal'>
                        <h2 class='text-center pb-3'>Delete Id</h2>
                        <form id='modalDelId' onSubmit='modalDelIdFunc(event)'>
                            <div class='mb-3'>
                                <label for='oldPass' class='form-label'>Password</label>
                                <input type='password' class='form-control' id='password' name='password'>
                            </div>
                            <button type='submit' class='btn btn-warning'>delete</button>
                            <div class='btn btn-danger' onClick='cancelPassChangeFunc()'>Cancel</div>
                        </form>
                    </div>`;
            
                myBody.insertBefore(modal,navBar)
            
                let modalOut = document.getElementById("modalOut");
                modalOut.style.setProperty("--modalOutDisplay","flex");
    
            }
        }
        
    }
    
    xhr.send(formData);
    // console.log(boolIsAdmin);
    
   
    
}

function modalDelIdFunc(e){
    e.preventDefault();
    let password = document.getElementById("password");

    if(password.value == ""){
        alert("Sorry, This Field is required")
    }else{
        let form = document.getElementById("modalDelId");
        let formData = new FormData(form);

        let url = "./profileBackIdDel.php";
        let xhr  = new XMLHttpRequest();
        xhr.open("POST",url,true);

        xhr.onload = ()=>{
            let data = xhr.responseText;
            
            if(data=="invalidPassword"){
                alert("Password Doesnt Matches with that of Database")
            }else if(data=="success"){
                // console.log(data);
                window.location= "../index.php";
            }
            else{
                alert("Some Error Occured")
            }
            
        }

        xhr.send(formData)
    }
    
}


function changePasswordFunc(){
    let myHtml = document.querySelector("html");
    myHtml.style.setProperty("--modalbackDisplay","block");
    let myBody = document.querySelector("body");

    let navBar = document.querySelector("nav");
    let modal = document.createElement("div");
    modal.setAttribute("class","container");
    modal.setAttribute("id","modalOut");

    modal.innerHTML = 
        `
        <div class='contaier m-3' id='modal'>
            <h2 class='text-center pb-3'>Change password</h2>
            <form id='modalChangePassForm' onSubmit='modalChangePassFunc(event)'>
                <div class='mb-3'>
                    <label for='oldPass' class='form-label'>Old Password</label>
                    <input type='password' class='form-control' id='oldPass' name='oldPass'>
                </div>
                <div class='mb-3'>
                    <label for='newPass' class='form-label'>new Password</label>
                    <input type='password' class='form-control' id='newPass' name='newPass'>
                </div>
                <button type='submit' class='btn btn-warning'>Change</button>
                <div class='btn btn-danger' onClick='cancelPassChangeFunc()'>Cancel</div>
            </form>
        </div>`;

    myBody.insertBefore(modal,navBar)

    let modalOut = document.getElementById("modalOut");
    modalOut.style.setProperty("--modalOutDisplay","flex");
}


function cancelPassChangeFunc(){
    window.location.reload();
    
}


function modalChangePassFunc(e){
    e.preventDefault();
    let oldPass = document.getElementById("oldPass");
    let newPass = document.getElementById("newPass");

    if(oldPass.value=="" || newPass.value == ""){
        alert("Sorry These field are Required")
    }else if(newPass.value.length < 6){
        alert("Password should be more than 6 character long")
    }else{
        let url = "./profileBackPass.php";

        let form = document.getElementById("modalChangePassForm")

        let formData = new FormData(form);

        let xhr = new XMLHttpRequest();

        xhr.open("POST",url,true);

        xhr.onload = ()=>{
            let data = xhr.responseText;
            // console.log(data); 
            if(data == "invalidPassword"){
                alert("Password Doesn't match with Database")
            }else if(data == "success"){
                alert("Password Updated Successfully");
                window.location.reload();
            }else{
                alert("some Problem occured");
            }          
        }

        xhr.send(formData)
    }
    // console.log(`Hello`);
    
}


let askPrivateSelect = document.getElementById("askPrivateSelect");
askPrivateSelect.addEventListener("change",()=>{
    let value = askPrivateSelect.value;
    let url = `./profileBack.php?showProfile=${value}`;

    let xhr = new XMLHttpRequest();
    xhr.open("GET",url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText
        console.log(data);
    }
    // console.log(askPrivateSelect.value);
    xhr.send();
    
})