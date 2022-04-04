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