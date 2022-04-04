



function getCategory(){
    let category = document.getElementById("category");
    let sortBy = document.getElementById("sortBy");
    let allRepliesContainer = document.getElementById("allReplies");

    let categoryValue = category.value;
    let sortByValue = sortBy.value;

    // console.log(sortByValue,categoryValue);

    let url = `../reply/replyBack.php?sortBy=${sortByValue}&category=${categoryValue}`;
    let xhr = new XMLHttpRequest();

    xhr.open("GET",url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;
        allRepliesContainer.innerHTML = data;
        callThese();
        editFunction();

        // console.log(data);
    }

    xhr.send();
    
}

function delFunc(qSno,aSno,rSno,username){
    let delConfirm = confirm("Do you really want to delete this reply? ");
    if(delConfirm){
        let url = `../reply/replyBackUpdate.php?del=true&qSno=${qSno}&aSno=${aSno}&rSno=${rSno}&user=${username}`;
        let xhr = new XMLHttpRequest();
        
        xhr.open("GET",url,true);

        xhr.onload = ()=>{
            let data = xhr.responseText;
            // console.log(data);
            if(data=="delSuccess"){
                window.location.reload();
            }
            
        }
        xhr.send();
    }
    // console.log(qSno,aSno,rSno,username);
    
}


function callThese(){

    let allReplies = document.querySelectorAll(".card-body");
    // console.log(allReplies);
    
    allReplies.forEach((element,index) => {
        let parentElem = element.children[0];
        let elem = parentElem.children[0];
        let elemAfter = parentElem.children[1];
    
        /* Old Way 
        // har line break k kitne char aate 
        let no_ofLineBreak = elem.innerHTML.split("\n");
        no_ofLineBreak = no_ofLineBreak.length-1;
        let realLen = elem.innerHTML.length - no_ofLineBreak;
        let boxWidth = elem.clientWidth;
        realLen = realLen + eval(no_ofLineBreak * boxWidth * 0.125 ); */

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




// Edit Option
function editFunction(){
    let editBtns = document.getElementsByClassName("editBtn");
    for (let btn of editBtns) {
       
        btn.addEventListener("click",(e)=>{
            
            
            let parentElem = e.target.parentNode.parentNode.parentNode.children[0];      
            // console.log(parentElem);
            

            // Getting the Sno
            let Sno = e.target.parentNode.children[0];
            Sno = Sno.getAttribute("onClick")    
            Sno = Sno.replace("delFunc","");
            Sno = Sno.replace("(","")    
            Sno = Sno.replace(")","")   
            Sno  = Sno.split(",");
            let qSno  = Sno[0]; 
            let aSno  = Sno[1];     
            let rSno  = Sno[2];     

            // console.log(qSno,aSno,rSno);
            
    
            let content = parentElem;            
            // console.log(content);
            
            // removing read more Btn 
            content = content.innerText.replace("Lessen","");
    
            let htmlDisplay = document.querySelector("html");
            let navbar = document.querySelector("nav");
            let body = document.querySelector("body");
            htmlDisplay.classList.add("htmlBlack");
    
    
            let modal = document.createElement("div");
            modal.setAttribute("class","modalQues")
            modal.innerHTML = `
            <div id='modal' class='card'>
                <div class='card-body'>
                    <h1 class='text-center card-title'>Edit your Reply<span id='modalUser'></span></h1>
                    <form>
                        <div class='mb-3'>
                            <label for='modalContent' class='form-control'>Your Reply</label>
                            <textarea class='form-control' id='modalContent' name='modalContent' ></textarea>
                        </div>
                    </form> 
                    <div>
                        <button class='btn btn-primary' onClick='modalEditFunc(${qSno},${aSno},${rSno})'>Edit</button>
                        <button class='btn btn-danger' onClick='modalCancelFunc()'>Cancel</button>
                    </div>
                </div>
            </div>`;
    
            
            body.insertBefore(modal,navbar);
    
    
            let intersectionObv = new IntersectionObserver(mod=>{
                
                if(mod[0].isIntersecting){
                    let modalDescinput = document.getElementById("modalContent");

                    modalDescinput.value = content;
                    
                }
                
            });
    
            intersectionObv.observe(modal)
     
            
        })
        
    }
}
    
editFunction()
    
    
    
function modalCancelFunc(){
    let htmlDisplay = document.querySelector("html");
    let navbar = document.querySelector("nav");
    let body = document.querySelector("body");
    htmlDisplay.classList.remove("htmlBlack");
    let modal = document.querySelector(".modalQues")

    body.removeChild(modal)

    
}
    
function modalEditFunc(qSno,aSno,rSno){ 
    
    let confirmed = confirm("Do you really want to edit your Reply");
    if(confirmed){

        let modalDescinput = document.getElementById("modalContent");
        modalDescinput = modalDescinput.value
    
        let formObject = {
            "qSno" : qSno,
            "aSno" : aSno,
            "rSno" : rSno,
            "desc" : modalDescinput
        }
    
    
        let formData = new FormData();
    
        for (const key in formObject) {
            formData.append(key,formObject[key])        
        }

        let url = "../reply/replyBackUpdate.php";
        let xhr = new XMLHttpRequest();

        xhr.open("POST",url,true)

        xhr.onload = ()=>{
            let data = xhr.responseText;
            if(data=="success"){
                window.location.reload();
            }
            else{
                alert("Some Error Occured")
            }
            // console.log(data);
            
        }

        xhr.send(formData)


        
    }

    
}


let searchForm = document.getElementById("search");
searchForm.firstElementChild.placeholder="Search my replies";

searchForm.addEventListener("submit",(e)=>{
    e.preventDefault();

    let input = searchForm.children[0].value;
    // console.log(input);
    let url = `./myRepliesSearch.php?search=${input}`;

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