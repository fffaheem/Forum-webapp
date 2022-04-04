let addBtn = document.getElementById("addBtn");
let parentElem = addBtn.parentElement.parentElement;


addBtn.addEventListener("click",()=>{
    parentElem.innerHTML = `

    <div class="input-group mb-3 mx-3">
        <span class="input-group-text" id="inputGroup-sizing-default">Category Name: </span>
        <input type="text" id="categoryInput" class="form-control">
    </div>

    <div class='mx-3'>
        <button type="button" id="addCategory" onClick='addCategoryFunc()' class="btn btn-primary">Add</button>
    </div>

    <div>
        <button type="button" id="cancelCategory" onClick='cancelFunc()' class="btn btn-danger">Cancel</button>
    </div>`;
    
})


cancelFunc = ()=>{
    window.location.reload();
}



addCategoryFunc = ()=>{
    // let elem = document.getElementById("addCategory");
    let categoryInput = document.getElementById("categoryInput");

    let categoryInputVal = categoryInput.value;

    // code for validation category Input 
    let valid = false;
    if(categoryInputVal != ""){
        valid = true;
    }


    if(valid){
        // let url = `./indexBack.php?addCategory=true&category=${categoryInputVal}`;
        let url = "./indexBack.php";
        
        let form = new FormData();
        form.append("category",categoryInputVal);

        let xhr = new XMLHttpRequest();
        xhr.open("POST",url,true);

        xhr.onload = ()=>{
            let data = xhr.responseText;
            if(data == "fail"){
                alert("Failure please try again later");
            }else{
                let allCategoryDiv = document.getElementById("allCategory");
                allCategoryDiv.innerHTML = data;
                categoryInput.value = "";
            }
        }
        xhr.send(form);

    }else{
        alert("Please Enter category name");
    }

    
    
}
