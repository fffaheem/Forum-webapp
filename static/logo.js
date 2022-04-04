// for Logo


let logoTogglerOut = document.getElementById("userMainImgTogglerOut");
let logoToggler = document.getElementById("userMainImgToggler");
let logo = document.getElementById("userMainImg");
let navbarTogglerBtn = document.querySelector(".navbar-toggler");



let myHtml = document.querySelector("html");

let resize = new ResizeObserver(entry=>{
    let myBox = entry[0];
    let boxWidth = myBox.contentRect.width;
    try{
        if(boxWidth < 992){
            logoToggler.style.display = "flex";
            logo.style.display = "none";
        }else{
            logoToggler.style.display = "none";
            logo.style.display = "flex";
    }  
    }catch{}
})

resize.observe(myHtml);

// let observer = new IntersectionObserver(entries=>{
//     for (let entry of entries) {
//         // console.log(entry.target);
//         let boolBtn_isShowing = false;
//         let boolLogo_isShowing = false;
//         if(entry.target.className == "navbar-toggler" && entry.isIntersecting){
//             // console.log(`hey`);
//             logoToggler.style.display = "none"
//             boolBtn_isShowing = true;
            
//         }

//         if(entry.target.id == "userMainImgToggler" && entry.isIntersecting){
//             boolLogo_isShowing = true;
//         }
        
//         if(boolLogo_isShowing || boolBtn_isShowing){
//             logoToggler.style.display = "none"

//         }
//         if(!entry.isIntersecting){
            
//         }
//     }
    

// })


/*
let observer = new IntersectionObserver(entries=>{
    for (let entry of entries) {
        // console.log(entry.target);
        let boolBtn_isShowing = false;
        let boolLogo_isShowing = false;
        if(entry.target.className == "userMainImg" && entry.isIntersecting){
            // console.log(`hey`);
            // logoToggler.style.display = "none"
            boolBtn_isShowing = true;
            
        }

        if(entry.target.id == "userMainImgToggler" && entry.isIntersecting){
            boolLogo_isShowing = true;
        }
        
        if(boolLogo_isShowing || boolBtn_isShowing){
            logoToggler.style.display = "flex"
            // logo.style.display = "flex";


        }
    }
    

})

observer.observe(navbarTogglerBtn)
observer.observe(logoToggler)
*/
