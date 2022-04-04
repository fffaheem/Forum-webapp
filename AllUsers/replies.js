function getCategory(user){
    let category = document.getElementById("category");
    let sortBy = document.getElementById("sortBy");
    let allRepliesContainer = document.getElementById("allReplies");

    let categoryValue = category.value;
    let sortByValue = sortBy.value;

    // console.log(sortByValue,categoryValue);

    let url = `./repliesBack.php?sortBy=${sortByValue}&category=${categoryValue}&user=${user}`;
    let xhr = new XMLHttpRequest();

    xhr.open("GET",url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;
        allRepliesContainer.innerHTML = data;
        callThese();
        // editFunction();

        // console.log(data);
    }

    xhr.send();
    
}

function callThese(){

    let allReplies = document.querySelectorAll(".card-body");
    // console.log(allReplies);
    
    allReplies.forEach((element,index) => {
        let parentElem = element.children[0];
        let elem = parentElem.children[0];
        let elemAfter = parentElem.children[1];
    
        //  getting no . of lines 

        let divHeight = +elem.offsetHeight   
        let lineHeight = window.getComputedStyle(elem).lineHeight.replace('px', ''); ;
        let lines = divHeight / lineHeight;  
        lines = Math.ceil(lines); 
        
        if(elem.innerHTML.length > 800){
            let data = elem.innerHTML;
            let wholeData = data;
            wholeData += `<div class='d-flex justify-content-end'> <button class='btn btn-primary' onClick="readMoreBtn(this)" >Lessen</button> </div>`;
            parentElem.removeChild(elem);
    
            let lessData = data.substring(0,800);
            lessData += "    . . . . . . . . . ";
            lessData += `<div class='d-flex justify-content-end'> <button class='btn btn-primary' onClick="readMoreBtn(this)" >Read More</button> </div>`;
            let wholeTag = document.createElement("p");
            wholeTag.setAttribute("class","card-text spaceRetainer");
            wholeTag.innerHTML = lessData;
            wholeTag.style.whiteSpace = "normal";
            parentElem.insertBefore(wholeTag,elemAfter);
    
            let wholeTagHidden = document.createElement("p");
            wholeTagHidden.setAttribute("class","card-text spaceRetainer");
            wholeTagHidden.innerHTML = wholeData;
            parentElem.insertBefore(wholeTagHidden,wholeTag)
            wholeTagHidden.style.display = "none";
            // elem.innerHTML = lessData;
        }else if(lines > 7){
            let data = elem.innerHTML;
            let wholeData = data;
            wholeData += `<div class='d-flex justify-content-end'> <button class='btn btn-primary' onClick="readMoreBtn(this)" >Lessen</button> </div>`;
            parentElem.removeChild(elem);
    
            let lessData = data.substring(0,800);
            lessData += "    . . . . . . . . . ";
            lessData += `<div class='d-flex justify-content-end'> <button class='btn btn-primary' onClick="readMoreBtn(this)" >Read More</button> </div>`;
            let wholeTag = document.createElement("p");
            wholeTag.setAttribute("class","card-text");
            wholeTag.innerHTML = lessData;
            wholeTag.style.whiteSpace = "normal";
            parentElem.insertBefore(wholeTag,elemAfter);
    
            let wholeTagHidden = document.createElement("p");
            wholeTagHidden.setAttribute("class","card-text spaceRetainer");
            wholeTagHidden.innerHTML = wholeData;
            parentElem.insertBefore(wholeTagHidden,wholeTag)
            wholeTagHidden.style.display = "none";
        }
    });
    
}
    
    
callThese();


function readMoreBtn(elem){
    let parent = elem.parentNode.parentNode.parentNode;
    let hiddenP = parent.children[0];
    let nonHiddenP = parent.children[1];

    if(hiddenP.style.display == "none"){
        nonHiddenP.style.display = "none";
        hiddenP.style.display = "block";
    }else{
        nonHiddenP.style.display = "block";
        hiddenP.style.display = "none";
    }
   
}



let searchForm = document.getElementById("search");
searchForm.firstElementChild.placeholder=`Search users replies`;

searchForm.addEventListener("submit",(e)=>{
    e.preventDefault();

    const urlParams = new URLSearchParams(window.location.search);
    const user = urlParams.get('user');

    let input = searchForm.children[0].value;

    let url = `./repliesSearch.php?user=${user}&search=${input}`;

    if(input != ""){
        let xhr = new XMLHttpRequest();
        xhr.open("GET",url,true);

        xhr.onload = ()=>{
            let data = xhr.responseText;
            try{
                let sortingForm = document.getElementById("sortBy").parentElement.parentElement.parentElement;
                sortingForm.removeChild(sortingForm.firstElementChild);
            }catch{

            }
            let allRepliesDiv = document.getElementById("allReplies");
            allRepliesDiv.innerHTML= data;

            callThese();

        }

        xhr.send();
    }else{
        window.location.reload();
    }
    
})


function searchCancelFunc(){
    window.location.reload();
}