const form = document.querySelector('.container form');
const user = document.querySelector('.container form input[name=user]').value;
const token = document.querySelector('.container form input[name=token]').value;
const perm = document.querySelector('.container form select[name=Permission');
var element = document.querySelector(".container .message_box");
var page = window;


document.addEventListener('DOMContentLoaded', function (e) {
    // values for permission
    show_permission();
})
form.addEventListener("change", function (e) {
    if (confirm("Czy na pewno chcesz edytować?")) {
        update_user(e.target);
        // console.log(e.target.name +" - "+ e.target.value);
        console.log("You pressed OK!");
        show_message('Dane zostały zaktualizowane!');
    } else {
        location.reload();
        console.log("You pressed Cancel!");
    }

})



function show_permission() {
    const myData = new FormData();
    myData.append('token', token);
    myData.append('user', user);
    myData.append('perm', perm.id);


    fetch("../includes/JS/Request/Req_user.php?case=1", {
        method: 'post',
        body: myData
    })
        .then(resp => resp.text())
        .then(resp => {
            //result for request
            perm.innerHTML = resp;
            // console.log(resp);
        })
        .catch(error => {
            //results for the errors conection
            if (error.status === 404) {
                console.log("Page not found");
            } else if (error.status === 500) {
                console.log("Server error");
            } else {
                console.log("Error connection " + error.status);
                console.log(error);
            }
        });
}


function update_user(data) {
    let user_id = user;
    let token   = document.querySelector('.container form input[name=token]').value;

    //data to send
    const userData = new FormData();
    userData.append("case", 2);
    userData.append("token", token);
    userData.append("user_id", user_id);
    userData.append("field", data.name);
    userData.append("value", data.value);


    fetch('../includes/JS/Request/Req_user.php?case=2', {
        method: 'post',
        body: userData
    })
        .then(resp => resp.text())
        .then(resp => {
            //result for request
            console.log(resp);
            // sleep(1500);
        })
        .catch(error => {
            //results for the errors conection
            if (error.status === 404) {
                console.log("Page not found");
            }else if (error.status === 500) {
                console.log("Server error");
            }else {
                console.log("Error connection " + error.status);
            }
        });
}

function show_message(content){
    window.parent.scroll(-10, -10);
    element.innerHTML = content;
    element.classList.add("message_box-active");
    setTimeout(()=>{
        element.classList.remove("message_box-active");
    },3000);
}

function sleep(milliseconds) {
    const date = Date.now();
    let currentDate = null;
    do {
        currentDate = Date.now();
    } while (currentDate - date < milliseconds);
}