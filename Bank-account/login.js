let service = document.getElementById("service");
let transact = document.getElementById("transact");
let alter = document.getElementById("alter");
service.addEventListener("change", function(){
    if (service.value=="transact"){
        if(transact.style.display === "none"){
            transact.style.display = "block";
            transact.style.borderColor = "red";
            transact.style.borderWidth = "2px";
            transact.style.borderStyle = "solid";
            transact.style.marginRight = "75%";
            transact.style.padding = "10px", "10px", "10px","0px";
        }
        else{
            transact.style.display = "none"
        }
        alter.style.display = "none";
    }
    else if(service.value=="alter"){
        if(alter.style.display === "none"){
            alter.style.display = "block";
            alter.style.borderColor = "red";
            alter.style.borderWidth = "2px";
            alter.style.borderStyle = "solid";
            alter.style.marginRight = "75%";
            alter.style.padding = "10px", "10px", "10px","0px";
        }
        else{
            alter.style.display = "none";
        }
        transact.style.display = "none"
    }
    else{
        transact.style.display = "none"
        alter.style.display = "none"
    }
})