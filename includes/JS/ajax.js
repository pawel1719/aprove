const form = document.querySelector('.container form');

function manage_agree(form_input) {
    var link        = document.URL;
    let name        = form_input.name;
    let user_id     = form_input.value;
    let status      = form_input.checked;
    let hash_agree  = link.substring(link.indexOf('id=') + 3, link.indexOf('&page'));
    let agreement   = document.querySelector('.container form input[name=agreemet]').value;
    let token       = document.querySelector('.container form input[name=token]').value;

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
    if (e.target.type == "checkbox") {
        if (e.target.name == "select_all") {
            if (e.target.checked == true) {
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
        inputs[i].checked = value
        if (value && inputs[i].name != "select_all") {
            inputs[i].parentElement.parentElement.classList.add('table-success');
            inputs[i].parentElement.parentElement.classList.remove('table-danger');
            manage_agree(inputs[i]);
        } else if (value == false && inputs[i].name != "select_all") {
            inputs[i].parentElement.parentElement.classList.remove('table-success');
            inputs[i].parentElement.parentElement.classList.add('table-danger');
            manage_agree(inputs[i]);
        }
    }
}





















// function values_to() {
//     const form = document.querySelectorAll(".container form input");
//     let data = '';
//
//     for (i = 0; i < form.length; i++)
//         if (form[i].checked) {
//             data += form[i].value + ',';
//         }
//     data = data.substr(0, data.length - 1);
//     console.log(data);
// }
//
//     //SELECT ALL
//     const formm = document.querySelector('.container form input[name=select_all]');
//     formm.addEventListener("change", function(){
//         const tmp_form = document.querySelectorAll('.container form input[type=checkbox]');
//         for(let i=1; i<tmp_form.length; i++) {
//             if(tmp_form[i].name != 'select_all')
//                 tmp_form[i].checked = this.checked;
//                 console.log(tmp_form[i].value + ' - ' + tmp_form[i].checked + ' - ' + tmp_form[i].name)
//         }
//         console.log('Set values ' + this.checked);
//     })
//
//
// function manage_agree(form_input) {
//     var link        = document.URL;
//     let name        = form_input.name;
//     let user_id     = form_input.value;
//     let status      = form_input.checked;
//     let agreement   = link.substring(link.indexOf('id=') + 3, link.indexOf('&page'))
//     let token       = document.querySelector('.container form input[name=token]').value;
//
//     // console.log('---------------------------------------');
//     // console.log('token=' + token + '&agreement=' + agreement + '&name=' + name + '&user_id=' + user_id + '&status=' + status);
//
//     //headers to send
//     const myHeader = new Headers();
//     myHeader.append("Content-Type", "text/plain");
//     myHeader.append("Accept", "*/*");
//     // console.log(myHeader);
//
//     //data to send
//     const myData = new FormData();
//     myData.append("token", token);
//     myData.append("agreement", agreement);
//     myData.append("name", name);
//     myData.append("user_id", user_id);
//     myData.append("status", status);
//
//     fetch('../includes/JS/Request/Request.php?case=1', {
//             method: 'POST',
//             // headers: myHeader,
//             body: myData
//         })
//         .then(resp => resp.text())
//         .then(resp => {
//             //result for request
//             console.log(resp);
//         })
//         .catch(error => {
//             //results for the errors conection
//             if (error.status === 404) {
//                 console.log("Page not found");
//             }else if (error.status === 500) {
//                 console.log("Server error");
//             }else {
//                 console.log("Error connection " + error.status);
//             }
//         });
//
// }
//
//
// const form = document.querySelectorAll('.container form input');
//
// for(var i=0; i<form.length; i++){
//     form[i].addEventListener("change", function(){
//         if(this.name != 'select_all') {
//             if (this.checked) {
//                 manage_agree(this);
//                 console.log('Zaznaczone ' + this.name + ' - ' + this.value);
//                 this.parentElement.parentElement.classList.remove('table-danger');
//                 this.parentElement.parentElement.classList.add('table-success');
//             } else {
//                 manage_agree(this);
//                 console.log('Odzaznaczone ' + this.name + ' - ' + this.value);
//                 this.parentElement.parentElement.classList.remove('table-success');
//                 this.parentElement.parentElement.classList.add('table-danger');
//             }
//         }
//     })
// }
