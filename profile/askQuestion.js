let askForm = document.getElementById("askForm");
let outcomeMssg = document.getElementById("outcomeMssg");
outcomeMssg.style.display="none";

askForm.addEventListener("submit",(e)=>{
    e.preventDefault();

    let formData = new FormData(askForm);
    let url = "./askQuestionBack.php";
    let xhr = new XMLHttpRequest();
    xhr.open("POST",url,true);

    xhr.onload = ()=>{
        let data = xhr.responseText;
        // console.log(data);
        outcomeMssg.style.display="block";
        outcomeMssg.innerHTML=data;
        let outcome = outcomeMssg.firstElementChild.id;

        if(outcome=="success"){
            askForm.reset();
        }
        
    }

    xhr.send(formData)
})


function outcomeBtn(){
    outcomeMssg.style.display="none";

}