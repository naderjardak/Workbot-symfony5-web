adminChatSource = null;
assistanceSource = null
chatRoomId = -1;
adminId = 15;
adminName = "Admin";
window.addEventListener('load', function() {
 connectAssitants();
console.log("mercure connected back office");
});
function connectAssitants() {

    //connect to mercure server
    const url = new URL("http://127.0.0.1:3000/.well-known/mercure");
    url.searchParams.append('topic', 'http://127.0.0.1:8000/assistans')
    assistanceSource = new EventSource(url);

    // declencher par la méthode openCloseChat dans reloadNotifications.js
    assistanceSource.addEventListener('message', function(event){
        //3éme etape connexion au chat
        showNotif(event.data);
    });
}
function showNotif(id) {
    // Get the snackbar DIV
    // document.getElementById("contractUrl").href ="http://localhost:8000/contrat/"+id;
    var x = document.getElementById("snackbar");
    x.addEventListener('click', function() {
        x.className = x.className.replace("show", "");
        assistanceSource.close();
        assistanceSource = null;
        const url = new URL("http://127.0.0.1:3000/.well-known/mercure");
        url.searchParams.append('topic', 'http://127.0.0.1:8000/chat/'+id)
        adminChatSource = new EventSource(url);
        chatRoomId = id;

        //Listen for messages
        adminChatSource.addEventListener('message', function(event){
            if(event.data != null) {
                data = JSON.parse(event.data);
                if(data.sender == -1) {
                    document.getElementById("texts").innerHTML = "Connected with <b>"+data.message+"</b><br><br>";
                } else if(data.sender == -2) {
                    // user left close all.
                } else {
                    addMessage(data, adminId);
                }
            }
        });
        $.post('http://localhost:8000/candidature/sendMessage/'+id, {message: "Connected", senderId: -1, senderName: adminName});
        document.getElementById("wrapperAdmin").style.display ="inherit";

    });

    // Add the "show" class to DIV
    x.className = "show";

    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 20000);
}



function sendMessageAdmin() {
    if(document.getElementById("adminChatInput").value == "")
        return;
    $.post('http://localhost:8000/candidature/sendMessage/'+chatRoomId, {message: document.getElementById("adminChatInput").value, senderId: adminId, senderName: adminName});
    document.getElementById("adminChatInput").value = "";
}

function sendAdminBykeyboard(event) {
    if(event.key == "Enter") {
        sendMessageAdmin();
    }
}

function terminateChat() {
    $.post('http://localhost:8000/candidature/sendMessage/'+chatRoomId, {message: "", senderId: -2, senderName: adminName});
    adminChatSource.close();
    adminChatSource = null;
    document.getElementById("wrapperAdmin").style.display ="none";
    document.getElementById("texts").innerHTML ="";
    connectAssitants();
}