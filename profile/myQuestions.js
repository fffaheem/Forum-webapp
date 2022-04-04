let allQues = document.getElementById("allQues");

function getCategory(){    
    let category = document.getElementById("category");
    let sortBy = document.getElementById("sortBy");
    // console.log(category.value,sortBy.value);

    let xhr = new XMLHttpRequest();
    $url = `./myQuestionsBack.php?sortBy=${sortBy.value}&category=${category.value}`;

    xhr.open("GET",$url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;
        allQues.innerHTML = data;

        callThese();
        
        editFunction()
        // console.log(data);
        
    }

    xhr.send();
    
}

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


function delFunc(sno,username){

    let confirmDel =  confirm("Do You really want to delete this Question");
    if(confirmDel){
        let url = `./myQuestionBackUpdate.php?del=true&qSno=${sno}&user=${username}`;
        let xhr = new XMLHttpRequest();

        xhr.open("GET",url,true);

        xhr.onload=()=>{
            let data = xhr.responseText;
            if(data=="delSuccess"){
                window.location.reload();
            }
            // console.log(data);
            
        }

        xhr.send();

    }   
    
}


function callThese(){

let allQuestions = document.querySelectorAll(".card-body");
allQuestions.forEach((element,index) => {
    let elem = element.children[2];
    let elemAfter = element.children[3];

      //  getting no . of lines 

      let divHeight = +elem.offsetHeight   
      let lineHeight = window.getComputedStyle(elem).lineHeight.replace('px', ''); ;
      let lines = divHeight / lineHeight;  
      lines = Math.ceil(lines); 
    //   console.log(lines);
      
  
    
    if(elem.innerHTML.length > 800){
        let data = elem.innerHTML;
        let wholeData = data;
        wholeData += `<div class='d-flex justify-content-end'> <button class='btn btn-primary' onClick="readMoreBtn(this)" >Lessen</button> </div>`;
        element.removeChild(elem);

        let lessData = data.substring(0,800);
        lessData += "    . . . . . . . . . ";
        lessData += `<div class='d-flex justify-content-end'> <button class='btn btn-primary' onClick="readMoreBtn(this)" >Read More</button> </div>`;
        let wholeTag = document.createElement("p");
        wholeTag.setAttribute("class","card-text spaceRetain");
        wholeTag.innerHTML = lessData;
        // let wholeTag = `<p class='card-text spaceRetain'> ${lessData} </p>`;
        element.insertBefore(wholeTag,elemAfter);

        let wholeTagHidden = document.createElement("p");
        wholeTagHidden.setAttribute("class","card-text spaceRetain");
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
        wholeTag.setAttribute("class","card-text ");
        wholeTag.innerHTML = lessData;
        // let wholeTag = `<p class='card-text spaceRetain'> ${lessData} </p>`;
        element.insertBefore(wholeTag,elemAfter);

        let wholeTagHidden = document.createElement("p");
        wholeTagHidden.setAttribute("class","card-text spaceRetain");
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





// Edit Option
function editFunction(){
let editBtns = document.getElementsByClassName("editBtn");
for (let btn of editBtns) {
   
    btn.addEventListener("click",(e)=>{
        
        
        let parentElem = e.target.parentNode.parentNode.children[1];      
        
        // Getting the Sno
        let Sno = e.target.parentNode.children[0];
        Sno = Sno.getAttribute("onClick")    
        Sno = Sno.replace("delFunc","");
        Sno = Sno.replace("(","")    
        Sno = Sno.replace(")","")   
        Sno  = Sno.split(",");
        Sno  = Sno[0]; 
        // console.log(Sno);


        let heading = parentElem.children[1].innerHTML;
        let content = parentElem.children[2];
        
        
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
                <h1 class='text-center card-title'>Edit your Question<span id='modalUser'></span></h1>
                <form>
                    <div class='mb-3'>
                        <label for='modalTitleName' class='form-label'>Title</label>
                        <input type='text' class='form-control' id='modalTitleName' name='modalTitleName' >
                    </div>
                    <div class='mb-3'>
                        <label for='modalContent' class='form-control'>Question Description</label>
                        <textarea class='form-control' id='modalContent' name='modalContent' ></textarea>
                    </div>
                </form> 
                <div>
                    <button class='btn btn-primary' onClick='modalEditFunc(${Sno})'>Edit</button>
                    <button class='btn btn-danger' onClick='modalCancelFunc()'>Cancel</button>
                </div>
            </div>
        </div>`;

        
        body.insertBefore(modal,navbar);


        let intersectionObv = new IntersectionObserver(mod=>{
            
            if(mod[0].isIntersecting){
                let modalHeadInput = document.getElementById("modalTitleName");
                let modalDescinput = document.getElementById("modalContent");

                modalHeadInput.value = heading;
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

function modalEditFunc(Sno){ 
    
    let confirmed = confirm("Do you really want to edit your Question");
    if(confirmed){

        let modalHeadInput = document.getElementById("modalTitleName");
        let modalDescinput = document.getElementById("modalContent");
        modalHeadInput = modalHeadInput.value
        modalDescinput = modalDescinput.value
    
        let formObject = {
            "Sno" : Sno,
            "title" : modalHeadInput,
            "desc" : modalDescinput
        }
    
    
        let formData = new FormData();
    
        for (const key in formObject) {
            formData.append(key,formObject[key])        
        }

        let url = "./myQuestionBackUpdate.php";
        let xhr = new XMLHttpRequest();

        xhr.open("POST",url,true)

        xhr.onload = ()=>{
            let data = xhr.responseText;
            if(data=="success"){
                window.location.reload();
            }
            else if(data="editFail"){
                alert("Question has been Already Answered You cannot Edit it ")
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
searchForm.firstElementChild.placeholder="Search my questions";

searchForm.addEventListener("submit",(e)=>{
    e.preventDefault();

    let input = searchForm.children[0].value;
    // console.log(input);
    let url = `./myQuestionSearch.php?search=${input}`;

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