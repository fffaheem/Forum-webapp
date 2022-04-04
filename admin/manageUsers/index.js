let searchForm = document.getElementById("search");
let searchInput = search.children[0];
searchForm.addEventListener("submit",(e)=>{
    e.preventDefault();

    let data = search.children[0].value;

    if(searchInput.value != ""){

        // console.log(data);
        let url = `./indexBack.php?search=${searchInput.value}`;
        let xhr = new XMLHttpRequest();
        xhr.open("GET",url,true);
        
        xhr.onload = ()=>{
            let data = xhr.responseText;
            let allUserContainer = document.getElementById("alluser");
            allUserContainer.innerHTML = data;
            
        }
        
        xhr.send();
    }

    
})



function searchCancelFunc(){
    window.location.reload();
    
}

searchForm.firstElementChild.placeholder="Search users";



function delFunc(e,username){
    
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
            }
            // console.log(data);
        }
        
        xhr.send(formData);
    }
    
}

function addAdminFunc(e,username){
    
    let confirmed = confirm("Do you Want to make this user Admin");
    if(confirmed){
        let formData = new FormData();
        formData.append("username",username)
        
        let url = "./indexAdd.php";

        let xhr = new XMLHttpRequest();
        xhr.open("POST",url,true);

        xhr.onload = ()=>{
            let data = xhr.responseText;
            if(data == "pass"){
                // let elem = e.parentElement.parentElement;
                let elem = e.parentElement.parentElement.parentElement.parentElement.parentElement.parentElement;
                let parentElem = elem.parentElement;
                parentElem.removeChild(elem);
            }
            // console.log(data);
        }
        
        xhr.send(formData);
    }
    
}


