function deleteAll(){
    let url = "notificationsBack.php?deleteAllNotification=true";
    let xhr = new XMLHttpRequest();
    xhr.open("GET",url,true);
    xhr.onload = ()=>{
        let data = xhr.responseText;
        if(data == "done"){
            window.location.reload();
        }else{
            alert("Something went wrong please try again later");
        }
    }
    xhr.send();
    
}


function specificDelete(e,sno){
    let elem = e.parentElement.parentElement.parentElement
    let parentElem = elem.parentElement;
    let url = `notificationsBack.php?deleteSpecific=${sno}`;
    let xhr = new XMLHttpRequest();
    xhr.open("GET",url,true);
    xhr.onload = ()=>{
        let data = xhr.responseText;
        if(data == "done"){
            parentElem.removeChild(elem)
        }else if(data == "doneClear"){
            window.location.reload();
        }else{
            alert("Something went wrong please try again later");
        }
    }
    xhr.send();
}