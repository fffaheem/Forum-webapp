const link = window.location.origin;

// console.log(link);

setInterval(() => {
    let url = `${link}/forumWebsite/partials/sessionChecker.php?check=true`;
    let xhr = new XMLHttpRequest();
    xhr.open("GET",url,true);
    xhr.onload = ()=>{
        let data = xhr.responseText;
        if(data == "notFound"){
            window.location.reload();
        }
        // console.log(data);
        
    }

    xhr.send();
}, 1000);