function ansLikeFunc(elem,qSno,aSno,answeredBy){
    // console.log(`sno = ${sno}`);

    let xhr = new XMLHttpRequest();
    url = `../question/questionAnsBack.php?like=true&qid=${qSno}&aid=${aSno}&answeredBy=${answeredBy}`;
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


let myLikedAnswer = document.getElementById("myLikedAnswer");
function getCategory(user){
    let category = document.getElementById("category");
    let sortBy = document.getElementById("sortBy");

    let url = `./likedAnswerBack.php?sortBy=${sortBy.value}&category=${category.value}&user=${user}`;
    let xhr = new XMLHttpRequest();
    xhr.open("GET",url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;
        myLikedAnswer.innerHTML = data;
        callThese();

        
    }

    xhr.send();
}


// For Read More Btn


function callThese(){

    let allAnswers = document.querySelectorAll(".card-body");
    allAnswers.forEach((element,index) => {
        let elem = element.children[1];
        let elemAfter = element.children[2];
    
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
            // let wholeTag = `<p class='card-text spaceRetain'> ${lessData} </p>`;
            element.insertBefore(wholeTag,elemAfter);
    
            let wholeTagHidden = document.createElement("p");
            wholeTagHidden.setAttribute("class","card-text spaceRetainer");
            wholeTagHidden.innerHTML = wholeData;
            element.insertBefore(wholeTagHidden,wholeTag)
            wholeTagHidden.style.display = "none";
            // elem.innerHTML = lessData;
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
    let hiddenP = parent.children[1];
    let nonHiddenP = parent.children[2];

    if(hiddenP.style.display == "none"){
        nonHiddenP.style.display = "none";
        hiddenP.style.display = "block";
    }else{
        nonHiddenP.style.display = "block";
        hiddenP.style.display = "none";
    }
   
}


let searchForm = document.getElementById("search");
searchForm.firstElementChild.placeholder=`Search users liked answers`;

searchForm.addEventListener("submit",(e)=>{
    e.preventDefault();

    const urlParams = new URLSearchParams(window.location.search);
    const user = urlParams.get('user');

    let input = searchForm.children[0].value;

    let url = `./likedAnswerSearch.php?user=${user}&search=${input}`;

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
            let myLikedAnswerDiv = document.getElementById("myLikedAnswer");
            myLikedAnswerDiv.innerHTML= data;

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