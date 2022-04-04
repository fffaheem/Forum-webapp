let formOut = document.getElementById("questionFormOut");
let form = document.getElementById("questionForm");
let lol = document.getElementById("lol");

// let ursl = document.location.href;
// console.log(ursl);

const urlParams = new URLSearchParams(window.location.search);
const qSno = urlParams.get('ques');
const username = urlParams.get('user');




alertMssg = `<div class='alert alert-success alert-dismissible fade show' id='success' role='alert'>
                        <strong>Success</strong> Your Answer has been submitted
                        <button type='button' onClick='outcomeBtn()' class='btn-close' data-bs-dismiss='alert' aria-label='Close'></button>
                    </div>`;



function fetchAnswerAgain(){
    let xhr = new XMLHttpRequest();

    let url = `./questionBack.php?ques=${qSno}&user=${username}`;
    xhr.open("GET",url,true);

    xhr.onload = () =>{
        let data = xhr.responseText;
        let answers = document.getElementById("answers");
        answers.innerHTML = data; 
    }

    xhr.send();
}

function answerFunc(e){

    e.preventDefault();
    
    let formData = new FormData(form);
    let url = `./questionBack.php?ques=${qSno}&user=${username}`;
    let xhr = new XMLHttpRequest();
    xhr.open("POST",url,true);


    xhr.onload = ()=>{
        let data = xhr.responseText;
        let div = document.createElement("div");
        div.setAttribute("id","myDiv");
        formOut.insertBefore(div,form);
        let myDiv = document.getElementById("myDiv");
        myDiv.innerHTML = data;
        let outcome = myDiv.firstElementChild.id;


        if(outcome=="success"){
            form.reset();
            fetchAnswerAgain();
            callThese();
        }

        
    }

    xhr.send(formData)
    
}

function outcomeBtn(){
    let div = document.getElementById("myDiv");
    formOut.removeChild(div);
}


function getCategory(){    
    let sortBy = document.getElementById("sortBy");

    let xhr = new XMLHttpRequest();
    let url = `./questionBack.php?ques=${qSno}&user=${username}&sortBy=${sortBy.value}`;

    xhr.open("GET",url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;
        // console.log(data);
        
        let answers = document.getElementById("answers");
        answers.innerHTML = data;
        callThese();
        callTheseReplies();


        
    }

    xhr.send();
    
}

function likeFunc(elem,sno,askedBy){
    // console.log(`sno = ${sno}`);

    let xhr = new XMLHttpRequest();
    url = `../profile/myQuestionsBack.php?like=true&id=${sno}&askedBy=${askedBy}`;
    xhr.open("GET",url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;

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

function ansLikeFunc(elem,sno,answeredBy){
    // console.log(`sno = ${sno}`);

    let xhr = new XMLHttpRequest();
    url = `./questionAnsBack.php?like=true&qid=${qSno}&aid=${sno}&answeredBy=${answeredBy}`;
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

form.addEventListener("submit",answerFunc);






function alertLike(elem){
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
        div.setAttribute("id","myDiv")
        parentElem.insertBefore(div,mainElem);
        div.innerHTML = 
        `<div class='alert alert-primary alert-dismissible fade show mx-3' role='alert'>
            <strong>Log-in: </strong> You need to be logged in to like
            <button type='button' class='btn-close' onClick = 'alertLikeBtn(this)' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>`;
    }
} 


function alertLikeBtn(elem){
    let parentElem = elem.parentNode.parentNode.parentNode;
    let mainElem = elem.parentNode.parentNode;
    parentElem.removeChild(mainElem);
    // parentElem.style.display="none";
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
        if(parentElem.children[i].id == "myAnsDiv"){
            proceed = false;
        }
    }

    if(proceed){
        let div = document.createElement("div");
        div.setAttribute("id","myAnsDiv");
        parentElem.insertBefore(div,mainElem);
        div.innerHTML = 
        `<div class='alert fs-6 alert-primary alert-dismissible fade show mx-3' role='alert'>
            <strong>Log-in: </strong> You need to be logged in to like
            <button type='button' class='btn-close' onClick = 'alertAnsLikeBtn(this)' data-bs-dismiss='alert' aria-label='Close'></button>
        </div>`;
    }
} 



function addReplyFunc(elem,qid,aid,aUsername,aEmail,qCategory){
    let parentElem = elem.parentNode;
    let reply = parentElem.children[1].value;


    let replyObj = {
        "qid" : qid,
        "aid" : aid,
        "aUser" : aUsername,
        "aEmail" : aEmail,
        "qCategory" : qCategory,
        "reply" : reply
    }
    let formData = new FormData();

    for (let keys in replyObj) {
        formData.append(keys,replyObj[keys])
        // console.log(keys,replyObj[keys]);
        
    }

    if(reply != "" && reply != null){

        let url = "../reply/replyBack.php";
        let xhr = new XMLHttpRequest();

        xhr.open("POST",url,true);

        xhr.onload = ()=>{
            let data = xhr.responseText;
            // console.log(data);

            if(data=="success"){
                window.location.reload();
                callTheseReplies();

            }
            
        }

        xhr.send(formData);
    }

    // The Problem with get is No White Spaces 
    /*
    let url = `../reply/replyBack.php?qid=${qid}&aid=${aid}&aUser=${aUsername}&aEmail=${aEmail}&qCategory=${qCategory}&reply=${reply}`;
    if(reply != "" && reply != null){

        let xhr = new XMLHttpRequest();

        xhr.open("GET",url,true);

        xhr.onload = ()=>{
            let data = xhr.responseText;
            // console.log(data);

            if(data=="success"){
                window.location.reload();
            }
            
        }

        xhr.send();
    }
    */


    
    
}




function reportQuesFunc(qSno,qUsername,qCategory){
    let confirmed = confirm("Do you want to Report this Question");
    if(confirmed){
        let report = document.getElementById("reportQues");
        let url = `./questionReport.php?ques=${qSno}&user=${qUsername}&category=${qCategory}`;

        let xhr = new XMLHttpRequest();

        xhr.open("GET",url,true);

        xhr.onload = ()=>{
            let data = xhr.responseText;
            // console.log(data);
            if(data == "notLoggedIn"){
                report.innerHTML = "<small class='fs-6'> Not Logged in <small>";
                setTimeout(() => {
                    report.innerHTML = "<i class='far fa-flag'></i> ";
                }, 2000);            
            }else if(data == "success"){
                report.innerHTML = "<i class='fas fa-flag'></i> ";
                // console.log(`hey`);
                
            }else if(data == "error"){
                report.innerHTML = "<small class='fs-6'>Unexpected Error<small>";
                setTimeout(() => {
                    report.innerHTML = "<i class='far fa-flag'></i> ";
                }, 2000); 
            }else{
                report.innerHTML = "<small class='fs-6'>Already reported<small>";
                setTimeout(() => {
                    report.innerHTML = "<i class='fas fa-flag'></i> ";
                }, 2000); 
            }
        }
        xhr.send();
    }
    
}

function reportAnsFunc(elem,qSno,aSno,aUsername,qCategory){
    let confirmed = confirm("Do you want to Report this Answer");
    if(confirmed){
        // let report = document.getElementById("reportAns");

        let url = `./answerReport.php?ques=${qSno}&ans=${aSno}&user=${aUsername}&category=${qCategory}`;

        let xhr = new XMLHttpRequest();

        xhr.open("GET",url,true);

        xhr.onload = ()=>{
            let data = xhr.responseText;
            // console.log(data);
            if(data == "notLoggedIn"){
                elem.innerHTML = "<small class='fs-6'> Not Logged in </small>";
                setTimeout(() => {
                    elem.innerHTML = "<i class='far fa-flag'></i> ";
                }, 2000);            
            }else if(data == "success"){
                elem.innerHTML = "<i class='fas fa-flag'></i> ";
                // console.log(`hey`);
                
            }else if(data == "error"){
                elem.innerHTML = "<small class='fs-6'>Unexpected Error </small>";
                setTimeout(() => {
                    elem.innerHTML = "<i class='far fa-flag'></i> ";
                }, 2000); 
            }else{
                elem.innerHTML = "<small class='fs-6'>Already reported </small>";
                setTimeout(() => {
                    elem.innerHTML = "<i class='fas fa-flag'></i> ";
                }, 2000); 
            }
        }
        xhr.send();
    }
    

    // console.log(`hey`);
    
}

function reportRepFunc(elem,qSno,aSno,rSno,rUsername,qCategory){
    let confirmed = confirm("Do you want to Report this Reply");
    if(confirmed){
        // let report = document.getElementById("reportAns");

        let url = `./replyReport.php?ques=${qSno}&ans=${aSno}&rep=${rSno}&user=${rUsername}&category=${qCategory}`;

        let xhr = new XMLHttpRequest();

        xhr.open("GET",url,true);

        xhr.onload = ()=>{
            let data = xhr.responseText;
            // console.log(data);
            if(data == "notLoggedIn"){
                elem.innerHTML = "<small class='fs-6'> Not Logged in </small>";
                setTimeout(() => {
                    elem.innerHTML = "<i class='far fa-flag'></i> ";
                }, 2000);            
            }else if(data == "success"){
                elem.innerHTML = "<i class='fas fa-flag'></i> ";
                // console.log(`hey`);
                
            }else if(data == "error"){
                elem.innerHTML = "<small class='fs-6'>Unexpected Error </small>";
                setTimeout(() => {
                    elem.innerHTML = "<i class='far fa-flag'></i> ";
                }, 2000); 
            }else{
                elem.innerHTML = "<small class='fs-6'>Already reported </small>";
                setTimeout(() => {
                    elem.innerHTML = "<i class='fas fa-flag'></i> ";
                }, 2000); 
            }
        }
        xhr.send();
    }
    
}




// Read More Btn 

// For question 

let question = document.querySelector("#questionFormOut");
let parentElem = question.parentNode;
elem = parentElem.children[3];
elemAfter = parentElem.children[4];
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
    wholeTag.style.wordBreak = "break-all";
    parentElem.insertBefore(wholeTag,elemAfter);
    
    
    let wholeTagHidden = document.createElement("p");
    wholeTagHidden.setAttribute("class","card-text spaceRetainer");
    wholeTagHidden.innerHTML = wholeData;
    parentElem.insertBefore(wholeTagHidden,wholeTag)
    wholeTagHidden.style.wordBreak = "break-all";        
    wholeTagHidden.style.display = "none"; 
}else if(lines  > 7){

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
    wholeTag.style.wordBreak = "break-all";
    parentElem.insertBefore(wholeTag,elemAfter);
    
    
    let wholeTagHidden = document.createElement("p");
    wholeTagHidden.setAttribute("class","card-text spaceRetainer");
    wholeTagHidden.innerHTML = wholeData;
    parentElem.insertBefore(wholeTagHidden,wholeTag)
    wholeTagHidden.style.wordBreak = "break-all";        
    wholeTagHidden.style.display = "none"; 

}
  

function readMoreBtn(elem){
    let parent = elem.parentNode.parentNode.parentNode;
    let hiddenP = parent.children[3];
    let nonHiddenP = parent.children[4];

    if(hiddenP.style.display == "none"){
        nonHiddenP.style.display = "none";
        hiddenP.style.display = "block";
    }else{
        nonHiddenP.style.display = "block";
        hiddenP.style.display = "none";
    }
    
}


// For  Answer
function callThese(){

    let allAnswers = document.querySelectorAll("#answers .card");
    allAnswers.forEach((element,index) => {
        let parentElem = element.children[2].children[0];
        let elem = parentElem.children[0];
        let elemAfter = parentElem.children[1];
    
        //  getting no . of lines 
            let divHeight = +elem.offsetHeight  
            // divHeight = +elem.clientHeight  
            let lineHeight = window.getComputedStyle(elem).lineHeight.replace('px', ''); ;
            // lineHeight = elem.li;
            let lines = divHeight / lineHeight;  
            lines = Math.ceil(lines);  
            // console.log(lines);
        
        if(elem.innerHTML.length > 800){
            let data = elem.innerHTML;
            let wholeData = data;
            wholeData += `<div class='d-flex justify-content-end'> <button class='btn btn-primary' onClick="readMoreBtnAns(this)" >Lessen</button> </div>`;
            parentElem.removeChild(elem);
    
            let lessData = data.substring(0,800);
            lessData += "    . . . . . . . . . ";
            lessData += `<div class='d-flex justify-content-end'> <button class='btn btn-primary' onClick="readMoreBtnAns(this)" >Read More</button> </div>`;
            let wholeTag = document.createElement("p");
            wholeTag.setAttribute("class","card-text spaceRetainer");
            wholeTag.innerHTML = lessData;
            parentElem.insertBefore(wholeTag,elemAfter);
    
            let wholeTagHidden = document.createElement("p");
            wholeTagHidden.setAttribute("class","card-text spaceRetainer");
            wholeTagHidden.innerHTML = wholeData;
            parentElem.insertBefore(wholeTagHidden,wholeTag)
            wholeTagHidden.style.display = "none";
        } else if(lines > 7){
            let data = elem.innerHTML;
            let wholeData = data;
            wholeData += `<div class='d-flex justify-content-end'> <button class='btn btn-primary' onClick="readMoreBtnAns(this)" >Lessen</button> </div>`;
            parentElem.removeChild(elem);
    
            let lessData = data.substring(0,800);
            lessData += "    . . . . . . . . . ";
            lessData += `<div class='d-flex justify-content-end'> <button class='btn btn-primary' onClick="readMoreBtnAns(this)" >Read More</button> </div>`;
            let wholeTag = document.createElement("p");
            wholeTag.setAttribute("class","card-text");
            wholeTag.innerHTML = lessData;
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

function readMoreBtnAns(elem){
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



// For Replies

function callTheseReplies(){

    let allReplies = document.querySelectorAll(".myTable");
    allReplies.forEach((element,index) => {
        let parentElem = element;
        let elem = parentElem.children[1];
        let elemAfter = parentElem.children[2];

        // har line break k kitne char aate
        let no_ofLineBreak = elem.innerHTML.split("\n");
        no_ofLineBreak = no_ofLineBreak.length-1;
        let realLen = elem.innerHTML.length - no_ofLineBreak;
        let boxWidth = elem.clientWidth;
        realLen = realLen + eval(no_ofLineBreak * boxWidth * 0.125 );
         

        if(elem.innerHTML.length > 200){
            let data = elem.innerHTML;
            let wholeData = data;
            wholeData += `<div class='d-flex justify-content-end'> <small class='repReadMore text-secondary' onClick="readMoreBtnRep(this)" >Lessen</small> </div>`;
            parentElem.removeChild(elem);
            
            let lessData = data.substring(0,200);
            lessData += " . . .";
            lessData += `<div class='d-flex justify-content-end'> <small class='repReadMore text-secondary' onClick="readMoreBtnRep(this)" >Read More</small> </div>`;
            let wholeTag = document.createElement("div");
            wholeTag.setAttribute("class","border-bottom border-secondary fs-6 text-white reply-replies");
            wholeTag.innerHTML = lessData;
            wholeTag.style.display.wordBreak = "break-all";
            wholeTag.style.whiteSpace = "normal";
            parentElem.insertBefore(wholeTag,elemAfter);
            
            let wholeTagHidden = document.createElement("div");
            wholeTagHidden.setAttribute("class","border-bottom border-secondary fs-6 text-white reply-replies spaceRetainer");
            wholeTagHidden.innerHTML = wholeData;
            parentElem.insertBefore(wholeTagHidden,wholeTag)
            wholeTagHidden.style.display.wordBreak = "break-all";
            wholeTagHidden.style.display = "none";
        }
        else if(realLen > 200){
            let data = elem.innerHTML;
            let wholeData = data;
            wholeData += `<div class='d-flex justify-content-end'> <small class='repReadMore text-secondary' onClick="readMoreBtnRep(this)" >Lessen</small> </div>`;
            parentElem.removeChild(elem);
            
            let lessData = data.substring(0,200);
            lessData += " . . .";
            lessData += `<div class='d-flex justify-content-end'> <small class='repReadMore text-secondary' onClick="readMoreBtnRep(this)" >Read More</small> </div>`;
            let wholeTag = document.createElement("div");
            wholeTag.setAttribute("class","border-bottom border-secondary fs-6 text-white reply-replies");
            wholeTag.innerHTML = lessData;
            wholeTag.style.display.wordBreak = "break-all";
            wholeTag.style.whiteSpace = "normal";
            parentElem.insertBefore(wholeTag,elemAfter);
            
            let wholeTagHidden = document.createElement("div");
            wholeTagHidden.setAttribute("class","border-bottom border-secondary fs-6 text-white reply-replies spaceRetainer");
            wholeTagHidden.innerHTML = wholeData;
            parentElem.insertBefore(wholeTagHidden,wholeTag)
            wholeTagHidden.style.display.wordBreak = "break-all";
            wholeTagHidden.style.display = "none";
            
        }
    }); 
    
}
    
    
callTheseReplies();

function readMoreBtnRep(elem){
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