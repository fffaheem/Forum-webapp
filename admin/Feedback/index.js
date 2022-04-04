function getCategory(){
   
    let sortBy = document.getElementById("sortBy");

    let feedbackContainer = document.getElementById("feedbackContainer");
    // console.log(category.value,sortBy.value);
    
    
    let url = `./indexBack.php?sortBy=${sortBy.value}`;
    let xhr = new XMLHttpRequest();

    xhr.open("GET",url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;
        feedbackContainer.innerHTML = data;
        // console.log(data);
        
    }

    xhr.send();
    
}