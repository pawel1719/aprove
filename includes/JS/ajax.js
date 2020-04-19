const form = document.querySelector('.container form');


function sleep (time) {
    return new Promise((resolve) => setTimeout(resolve, time));
}

function manage_agree(form_input) {
    var link            = document.URL;
    let name            = form_input.name;
    let user_id         = form_input.value;
    let status          = form_input.checked;
    let hash_agree      = link.substring(link.indexOf('id=') + 3, link.indexOf('&page'));
    let agreement       = document.querySelector('.container form input[name=agreemet]').value;
    let token           = document.querySelector('.container form input[name=token]').value;

    // console.log('---------------------------------------');
    // console.log('token=' + token + '&agreement=' + agreement + '&name=' + name + '&user_id=' + user_id + '&status=' + status);

    //headers to send
    const myHeader = new Headers();
    myHeader.append("Content-Type", "text/plain");
    myHeader.append("Accept", "*/*");
    // console.log(myHeader);

    //data to send
    const myData = new FormData();
    myData.append("token", token);
    myData.append("agreement", agreement);
    myData.append("hash_agree", hash_agree);
    myData.append("name", name);
    myData.append("user_id", user_id);
    myData.append("status", status);


    fetch('../includes/JS/Request/Request.php?case=1', {
            method: 'POST',
            // headers: myHeader,
            body: myData
        })
        .then(resp => resp.text())
        .then(resp => {
            //result for request
            console.log(resp);
            sleep(1500);
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

form.addEventListener("change", function (e) {
    if (e.target.type === "checkbox") {
        if (e.target.name === "select_all") {
            if (e.target.checked === true) {
                changeAllValues(true);
            } else {
                changeAllValues(false);
            }
        } else {
            if (e.target.checked) {
                e.target.parentElement.parentElement.classList.add('table-success');
                e.target.parentElement.parentElement.classList.remove('table-danger');
                manage_agree(e.target);
            } else {
                e.target.parentElement.parentElement.classList.remove('table-success');
                e.target.parentElement.parentElement.classList.add('table-danger');
                manage_agree(e.target);
            }
        }

    }

})

function changeAllValues(value) {
    const inputs = document.querySelectorAll(".container form input[type=checkbox]");
    for (var i = 0; i < inputs.length; i++) {
        if (inputs[i].checked != value) {
            inputs[i].checked = value;
            manage_agree(inputs[i]);
        }
        if (value == true && inputs[i].name != "select_all") {
            inputs[i].parentElement.parentElement.classList.remove('table-danger');
            inputs[i].parentElement.parentElement.classList.add('table-success');
        } else if (value == false && inputs[i].name != "select_all") {
            inputs[i].parentElement.parentElement.classList.remove('table-success');
            inputs[i].parentElement.parentElement.classList.add('table-danger');
        }
    }
}

function sleep(milliseconds) {
    const date = Date.now();
    let currentDate = null;
    do {
        currentDate = Date.now();
    } while (currentDate - date < milliseconds);
}