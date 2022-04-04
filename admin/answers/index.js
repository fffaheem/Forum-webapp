
function ansDeleteFunc(e,sno){
    let elem = e.parentElement.parentElement.parentElement.parentElement;
    let parentElem = elem.parentElement;

    let confirmed = confirm("Do you really want to delete this?");
    if(confirmed){
    
        let url = `./indexBack.php?del=true&deleteSno=${sno}`;
    
        let xhr = new XMLHttpRequest();
    
        xhr.open("GET",url,true);
    
        xhr.onload = ()=>{
            let data = xhr.responseText;
            if(data == "fail"){
                alert("Please try again later");
            }else{
                parentElem.removeChild(elem);
            }
            readMore();

        }
        xhr.send();
        
    }
    
}


function getCategory(){
    let category = document.getElementById("category");
    let sortBy = document.getElementById("sortBy");

    let answerContainer = document.getElementById("answerContainer");
    // console.log(category.value,sortBy.value);
    
    
    let url = `./indexBack.php?sortBy=${sortBy.value}&category=${category.value}`;
    let xhr = new XMLHttpRequest();

    xhr.open("GET",url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;
        answerContainer.innerHTML = data;
        readMore();
        
    }

    xhr.send();
    
}


function readMore(){
    let cardData = document.querySelectorAll(".ansCard .card-body .spaceRetain");


    for(let i = 0 ; i < cardData.length ; i++){

        let cards = cardData[i];
        
        //  getting no . of lines 
        let divHeight = cards.offsetHeight  
        // divHeight = +elem.clientHeight  
        let lineHeight = window.getComputedStyle(cards).lineHeight.replace('px', ''); ;
        // lineHeight = elem.li;
        let lines = divHeight / lineHeight;  
        lines = Math.ceil(lines);  


        if(lines > 10){
            // Making a copy of less Data
            let newLessDataElem = document.createElement("p");
            newLessDataElem.setAttribute("class","card-text spaceRetain");
            let newLessData =  cards.innerHTML.substring(0,200);
            newLessData+= " <span class='adminReadMore' onClick='showMore(this)'>   . . . . . . </span> ";
            newLessDataElem.innerHTML = newLessData;
            //----------

            cards.innerHTML+= "<button class='btn btn-primary adminReadLess' onClick='showLess(this)'> Lessen </button>";
            let parentElem = cards.parentElement;
            parentElem.insertBefore(newLessDataElem,cards);
            cards.style.display = "none";


        }
        
    }
    
}


function showMore(e) {
    let lessData = e.parentElement;
    let moreData = e.parentElement.nextElementSibling
    lessData.style.display = "none";
    moreData.style.display = "";
    
}

function showLess(e) {
    let moreData = e.parentElement;
    let lessData = e.parentElement.previousElementSibling
    lessData.style.display = "";
    moreData.style.display = "none";
    
}

readMore();


let searchForm = document.getElementById("search");
searchForm.firstElementChild.placeholder="Search all Answers";

searchForm.addEventListener("submit",(e)=>{
    e.preventDefault();
    let searchFormValue = searchForm.firstElementChild.value;
    
    let answerContainer = document.getElementById("answerContainer");

    let sortingForm = document.getElementById("sortingForm");
    let divElem = `<div class = 'container my-4' > <h3> Showing search results for  "<span class='text-danger' id = 'searchVal'></span>" </h3> </div>`;
    sortingForm.innerHTML = divElem;
    let searchVal = document.getElementById("searchVal");
    searchVal.innerText = searchFormValue
     
    let url =  `./indexSearch.php?search=${searchFormValue}`;


    let xhr = new XMLHttpRequest();
    xhr.open("GET",url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;
        
        answerContainer.innerHTML = data;
        readMore();
    }

    xhr.send();

    
})


searchCancelFunc = ()=>{
    window.location.reload();
}