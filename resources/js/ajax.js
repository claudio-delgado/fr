/*
//AJAX - JS way
let xmlhttp = new XMLHttpRequest();
xmlhttp.onreadystatechange = function() {
    if(xmlhttp.readyState == XMLHttpRequest.DONE){
        if(xmlhttp.status == 200){
            //Callback
            console.log(JSON.parse(xmlhttp.responseText));
        } else if(xmlhttp.status == 400) {
            alert('Error 400');
        } else {
            alert('Otro error distinto de 200 y 400')
        }
    }
}
xmlhttp.open("POST", urlToCall, true)
xmlhttp.setRequestHeader('Content-type', 'application/json');
xmlhttp.send(JSON.stringify(dataToSend));

//AJAX - Jquery's way
$.ajax({
    headers: {'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')},
    type: 'post',
    dataType: 'json',
    url: urlToCall,
    data: dataToSend
})

//AJAX - New JS way
async function post(url, data){
    const response = 
        fetch(url, {
            method: 'POST',
            headers: {
                'Content-type': 'application/json'
            },
            body: JSON.stringify(data)
        });
    debugger;
    return (response);
}
post(urlToCall, dataToSend)
.then(dataReceived => {
    debugger;
    console.log(dataReceived);
});
*/

const post = (urlToCall, dataToSend, callBack) => {
    fetch(urlToCall, {
        method: 'POST',
        body: JSON.stringify(dataToSend),
        headers: {
                    'Content-type': 'application/json'
                }
    }).then(res => res.json())
    .catch(error => console.error('Error:', error))
    .then(callBack())
}