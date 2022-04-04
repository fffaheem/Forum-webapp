
function alertLike(elem){
    let parentElem = elem.parentNode.parentNode.firstElementChild;
    parentElem.style.display="block";
    parentElem.innerHTML = 
    `<div class='alert alert-primary alert-dismissible fade show mx-3' role='alert'>
        <strong>Log-in: </strong> You need to be logged in to like
        <button type='button' class='btn-close' onClick = 'alertLikeBtn(this)' data-bs-dismiss='alert' aria-label='Close'></button>
    </div>`;

} 


function alertLikeBtn(elem){
    let parentElem = elem.parentNode.parentNode.parentNode.firstElementChild;
    parentElem.style.display="none";
}


function alertAnsLikeBtn(elem){
    let parentElem = elem.parentNode.parentNode.parentNode;
    let mainElem = elem.parentNode.parentNode;
    parentElem.removeChild(mainElem);
}

function alertAnsLike(elem){
    let parentElem = elem.parentNode.parentNode;
    let mainElem = elem.parentNode;

    let proceed = true;

    for(let i = 0 ; i < parentElem.children.length ; i++){
        if(parentElem.children[i].id == "myDiv"){
            proceed = false;
        }
    }

   if(proceed){
        let div = document.createElement("div");
        div.setAttribute("id","myDiv");
        // parentElem.appendChild(div);
        parentElem.insertBefore(div,mainElem);
        div.innerHTML = 
        `<div class='alert fs-6 alert-primary alert-dismissible fade show mx-3' role='alert'>
            <strong>Log-in: </strong> You need to be logged in to like
            <button type='button' class='btn-close' onClick = 'alertAnsLikeBtn(this)' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>`;
    }
} 


function getCategory(){    
    let category = document.getElementById("category");
    let sortBy = document.getElementById("sortBy");
    // console.log(category.value,sortBy.value);

    let xhr = new XMLHttpRequest();
    $url = `./indexBack.php?sortBy=${sortBy.value}&category=${category.value}`;

    xhr.open("GET",$url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;
        allQues.innerHTML = data;
        callThese()
        // console.log(data);
        
    }

    xhr.send();
    
}

function likeFunc(elem,sno,askedBy){
    // console.log(`sno = ${sno}`);

    let xhr = new XMLHttpRequest();
    url = `./indexBack.php?like=true&id=${sno}&askedBy=${askedBy}`;
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


// function ansLikeFunc(qSno,qUsername){
//     window.location = `./question/question.php?ques=${qSno}&user=${qUsername}`;
// }

function ansLikeFunc(elem,qsno,sno,answeredBy){
    // console.log(`sno = ${sno}`);

    let xhr = new XMLHttpRequest();
    url = `./question/questionAnsBack.php?like=true&qid=${qsno}&aid=${sno}&answeredBy=${answeredBy}`;
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

function callThese(){
let allQues = document.querySelectorAll(".card-body > p");
let allAns = document.querySelectorAll(".card-body > blockquote > p");

allQues.forEach(element => {
    //  getting no . of lines 

    let divHeight = +element.offsetHeight   
    let lineHeight = window.getComputedStyle(element).lineHeight.replace('px', ''); ;
    let lines = divHeight / lineHeight;  
    lines = Math.ceil(lines); 

    if(element.innerHTML.length > 400){
        let data = element.innerHTML;
        let lessData = data.substring(0,400);
        lessData += "  . . . . . . .";
        element.innerHTML = lessData;
    }else if(lines > 10){
        element.style.whiteSpace = "normal";
        let data = element.innerHTML;
        let lessData = data.substring(0,400);
        lessData += "  . . . . . . .";
        element.innerHTML = lessData;
    }
    // console.log(element);
    
});

allAns.forEach(element => {
    //  getting no . of lines 

    let divHeight = +element.offsetHeight   
    let lineHeight = window.getComputedStyle(element).lineHeight.replace('px', ''); ;
    let lines = divHeight / lineHeight;  
    lines = Math.ceil(lines); 

    if(element.innerHTML.length > 400){
        let data = element.innerHTML;
        let lessData = data.substring(0,400);
        lessData += "  . . . . . . .";
        element.innerHTML = lessData;
    }else if(lines > 7){
        element.style.whiteSpace = "normal";
        let data = element.innerHTML;
        let lessData = data.substring(0,400);
        lessData += "  . . . . . . .";
        element.innerHTML = lessData;
    }
    // console.log(element);
    
});

}

callThese();


window.onload = callThese();

// console.log(allAns);



let searchForm = document.getElementById("search");
searchForm.addEventListener("submit",(e)=>{
    e.preventDefault();

    let input = searchForm.children[0].value;
    // console.log(input);
    let url = `./indexSearch.php?search=${input}`;

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
            let allQuesDiv = document.getElementById("allQues");
            allQuesDiv.innerHTML= data;

            // callThese();

        }

        xhr.send();
    }else{
        window.location.reload();
    }
    
})

// searchForm.firstElementChild.addEventListener("input",()=>{
//     if(searchForm.firstElementChild.value == ""){
//         window.location.reload();
//     }
// })

function searchCancelFunc(){
    window.location.reload();
}