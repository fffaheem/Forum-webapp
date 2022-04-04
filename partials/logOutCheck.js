
setInterval(() => {
    let value = localStorage.getItem("isLoggedIn");
    if(value == null || value == ""){
        window.location.reload();
    }
}, 1000);