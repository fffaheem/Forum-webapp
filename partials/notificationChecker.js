
setInterval(() => {
    let url = `${link}/forumWebsite/partials/notificationBack.php?update='true'`;
    let xhr = new XMLHttpRequest();
    xhr.open("GET",url,true);
    xhr.onload = ()=>{
        let data = xhr.responseText;

        try{
            let notificationContainer = document.getElementById("notificationContainer");
            notificationContainer.innerHTML = data;
        }catch{

        }
        // console.log(data);
        
    }

    xhr.send();
}, 1000);