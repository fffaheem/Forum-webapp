let sendAllBtn = document.getElementById("sendAll")
let sendOneBtn = document.getElementById("sendOne")
let adminMessage = document.getElementById("adminMessage");
let myModal = document.getElementById("myModal");

sendAllBtn.addEventListener("click",()=>{
    if(adminMessage.value != ""){
        
        let confirmed = confirm("Do you really want to send Message to everyone? ")
        // let confirmed = true;
        if(confirmed){
            let formData = new FormData();
            formData.append("message",adminMessage.value);
            formData.append("sendAll","true");
            formData.append("username","");

            let url = "./indexBack.php";

            let xhr = new XMLHttpRequest();

            xhr.open("POST",url,true);

            xhr.onload = ()=>{
                let data = xhr.responseText;
                // console.log(data)
                if(data == "done"){
                    alert("Message Sent successfully");
                }else{
                    alert("Something went wrong, please try again later");
                }
                window.location.reload();
            }

            xhr.send(formData);
        }
    }else{
        alert("Message box empty")
    }
})

sendOneBtn.addEventListener("click",(e)=>{
    if(adminMessage.value != ""){
        myModal.showModal()
        myModal.innerHTML = 
        `<div class='container fs-3 d-flex justify-content-end' onclick='CancelFunc()'> <i class='fas fa-times cancelSearch'></i> </div>
        <div class='container my-4'>
            <h1 class='text-center'>All Users</h1>
            <div class='input-group flex-nowrap'>
                <input type='text' class='form-control fs-7' placeholder='Search' id='searchBox'>
                <span class='input-group-text btn btn-secondary' onClick='searchUsersFunc()' id='searchUser'>Search</span>
            </div>
            <div class='container my-4' id='allUsersContainer'>

            </div>
        </div>`;

        let allUsersContainer = document.getElementById("allUsersContainer");

        let xhr = new XMLHttpRequest();
        let url = "./allUsers.php?all=true";

        xhr.open("GET",url,true);

        xhr.onload = ()=>{
            let data = xhr.responseText;
            allUsersContainer.innerHTML = data;
        }
        xhr.send();

    }else{
        alert("Message box empty");
    }
})

function searchUsersFunc(){
    let searchBox = document.getElementById("searchBox");
    let allUsersContainer = document.getElementById("allUsersContainer");
    // console.log(searchBox.value)
    if(searchBox.value != ""){
        let xhr = new XMLHttpRequest();
        let url = `./allUsers.php?search=${searchBox.value}`;
        xhr.open("GET",url,true);

        xhr.onload = ()=>{
            let data = xhr.responseText;
            allUsersContainer.innerHTML = data;
            // console.log(data);
        }

        xhr.send();
    }
}

function searchCancelFunc(){

    let allUsersContainer = document.getElementById("allUsersContainer");
    let searchBox = document.getElementById("searchBox");
    searchBox.value = "";


    let xhr = new XMLHttpRequest();
    let url = "./allUsers.php?all=true";

    xhr.open("GET",url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;
        allUsersContainer.innerHTML = data;
    }
    xhr.send();

}

function sendOneFunc(username){
    if(adminMessage.value != ""){
        
        let confirmed = confirm(`Do you really want to send Message to ${username}? `);
        if(confirmed){
            let formData = new FormData();
            formData.append("message",adminMessage.value);
            formData.append("sendAll","false");
            formData.append("username",username);

            let url = "./indexBack.php";

            let xhr = new XMLHttpRequest();

            xhr.open("POST",url,true);

            xhr.onload = ()=>{
                let data = xhr.responseText;
                // console.log(data)
                if(data == "done"){
                    alert("Message Sent successfully");
                }else{
                    alert("Something went wrong, please try again later");
                }
                window.location.reload();
            }

            xhr.send(formData);
        }
    }else{
        alert("Message box empty")
    }
    
}

function CancelFunc(){
    myModal.innerHTML = "";
    myModal.close();
}