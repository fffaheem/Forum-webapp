function likeFunc(elem,sno,askedBy){
    // console.log(`sno = ${sno}`);

    let xhr = new XMLHttpRequest();
    url = `./myQuestionsBack.php?like=true&id=${sno}&askedBy=${askedBy}`;
    xhr.open("GET",url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;
        // console.log(data);
        if(data=="success"){
            // console.log(elem.parentNode.lastElementChild);
            let no_ofLike = elem.parentNode.lastElementChild;
            let likeVal = no_ofLike.innerHTML;
            no_ofLike.innerHTML = eval(likeVal+'+1');
            elem.classList.remove("far");
            elem.classList.add("fas");

        }
        else{
            let no_ofLike = elem.parentNode.lastElementChild;
            let likeVal = no_ofLike.innerHTML;
            no_ofLike.innerHTML = eval(likeVal+'-1');
            elem.classList.remove("fas");
            elem.classList.add("far");
        }
        
    }


    xhr.send();
    
}


let myLikedQues = document.getElementById("myLikedQues");
function getCategory(){
    let category = document.getElementById("category");
    let sortBy = document.getElementById("sortBy");

    let url = `./likedQuestionBack.php?sortBy=${sortBy.value}&category=${category.value}`;
    let xhr = new XMLHttpRequest();
    xhr.open("GET",url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;
        // console.log(data);

        myLikedQues.innerHTML = data;

        callThese();
        
    }

    xhr.send();
}





function callThese(){

    let allAnswers = document.querySelectorAll(".card-body");
    allAnswers.forEach((element,index) => {
        let elem = element.children[2];
        let elemAfter = element.children[3];
    
                //  getting no . of lines 

                let divHeight = +elem.offsetHeight   
                let lineHeight = window.getComputedStyle(elem).lineHeight.replace('px', ''); ;
                let lines = divHeight / lineHeight;  
                lines = Math.ceil(lines); 
        
        if(elem.innerHTML.length > 800){
            let data = elem.innerHTML;
            let wholeData = data;
            wholeData += `<div class='d-flex justify-content-end'> <button class='btn btn-primary' onClick="readMoreBtn(this)" >Lessen</button> </div>`;
            element.removeChild(elem);
    
            let lessData = data.substring(0,800);
            lessData += "    . . . . . . . . . ";
            lessData += `<div class='d-flex justify-content-end'> <button class='btn btn-primary' onClick="readMoreBtn(this)" >Read More</button> </div>`;
            let wholeTag = document.createElement("p");
            wholeTag.setAttribute("class","card-text spaceRetainer");
            wholeTag.innerHTML = lessData;
            element.insertBefore(wholeTag,elemAfter);
    
            let wholeTagHidden = document.createElement("p");
            wholeTagHidden.setAttribute("class","card-text spaceRetainer");
            wholeTagHidden.innerHTML = wholeData;
            element.insertBefore(wholeTagHidden,wholeTag)
            wholeTagHidden.style.display = "none";
        }else if(lines > 7){
            let data = elem.innerHTML;
            let wholeData = data;
            wholeData += `<div class='d-flex justify-content-end'> <button class='btn btn-primary' onClick="readMoreBtn(this)" >Lessen</button> </div>`;
            element.removeChild(elem);
    
            let lessData = data.substring(0,800);
            lessData += "    . . . . . . . . . ";
            lessData += `<div class='d-flex justify-content-end'> <button class='btn btn-primary' onClick="readMoreBtn(this)" >Read More</button> </div>`;
            let wholeTag = document.createElement("p");
            wholeTag.setAttribute("class","card-text");
            wholeTag.innerHTML = lessData;
            element.insertBefore(wholeTag,elemAfter);

            let wholeTagHidden = document.createElement("p");
            wholeTagHidden.setAttribute("class","card-text spaceRetainer");
            wholeTagHidden.innerHTML = wholeData;
            element.insertBefore(wholeTagHidden,wholeTag)
            wholeTagHidden.style.display = "none";
        }  
    });
    
}
    
    
callThese();

function readMoreBtn(elem){
    let parent = elem.parentNode.parentNode.parentNode;
    let hiddenP = parent.children[2];
    let nonHiddenP = parent.children[3];

    if(hiddenP.style.display == "none"){
        nonHiddenP.style.display = "none";
        hiddenP.style.display = "block";
    }else{
        nonHiddenP.style.display = "block";
        hiddenP.style.display = "none";
    }

    // console.log(nonHiddenP.style.display);
    
}


let searchForm = document.getElementById("search");
searchForm.firstElementChild.placeholder="Search liked questions";

searchForm.addEventListener("submit",(e)=>{
    e.preventDefault();

    let input = searchForm.children[0].value;
    // console.log(input);
    let url = `./likedQuestionSearch.php?search=${input}`;

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
            let myLikedQuesDiv = document.getElementById("myLikedQues");
            myLikedQuesDiv.innerHTML= data;

            callThese();
            editFunction();


        }

        xhr.send();
    }else{
        window.location.reload();
    }
    
})


function searchCancelFunc(){
    window.location.reload();
}