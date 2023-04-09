userId = 12;
userName = "Ameny";
chatOpen = false;
chatSource = null;
window.addEventListener('load', function() {
    const url = new URL("http://127.0.0.1:3000/.well-known/mercure");
    url.searchParams.append('topic', 'http://127.0.0.1:8000/addContract/'+userId)
    const eventSource = new EventSource(url);
    eventSource.addEventListener('message', function(event){
        if(event.data != null) {
            showAccepted(event.data);
        }
    });
console.log("mercure connected");
});

function showAccepted(id) {
    // Get the snackbar DIV
    document.getElementById("contractUrl").href ="http://localhost:8000/contrat/"+id;
    var x = document.getElementById("snackbar");

    // Add the "show" class to DIV
    x.className = "show";

    setTimeout(function(){ x.className = x.className.replace("show", ""); }, 10000);
}

    function openCloseChat() {
    if (!chatOpen) {
        chatOpen = true;
        $.get('http://localhost:8000/candidature/getAssistance/'+userId); // premier etape connexion au chat.

        const url = new URL("http://127.0.0.1:3000/.well-known/mercure");
        url.searchParams.append('topic', 'http://127.0.0.1:8000/chat/'+userId)
        chatSource = new EventSource(url);
        chatSource.addEventListener('message', function(event){
            if(event.data != null) {
                data = JSON.parse(event.data);
                if(data.sender == -1) {
                    document.getElementById("texts").innerHTML = "Connected with <b>"+data.senderName+"</b><br><br>";
                    document.getElementById("loaderDiv").style.display = "none";
                    document.getElementById("chatForm").style.display = "flex";
                    document.getElementById("chatForm").style.opacity = "1";
                    document.getElementById("texts").style.display = "inherit";
                } else if(data.sender == -2) {
                    // user left close all.
                    data.message = "Chat terminé par le conseiller";
                    addMessage(data, -2);
                    chatSource.close();
                    chatSource = null;
                } else {
                    addMessage(data, userId);
                }
            }
        });

    }
}

function sendMessageUser() {
    if(document.getElementById("userChatInput").value == "")
        return;
    $.post('http://localhost:8000/candidature/sendMessage/'+userId, {message: document.getElementById("userChatInput").value, senderId: userId, senderName: userName});
    document.getElementById("userChatInput").value = "";
}

function sendUserBykeyboard(event) {
    if(event.key == "Enter") {
        sendMessageUser();
    }
}

function restartChat() {
    document.getElementById("texts").innerHTML = "";
    document.getElementById("chatForm").style.opacity = "1";
    document.getElementById("userChatInput").disable = false;
    document.getElementById("userChatButton").disable = false;
    document.getElementById("loaderDiv").style.display = "inherit";
    document.getElementById("chatForm").style.display = "none";
    document.getElementById("texts").style.display = "none";
    chatOpen = false;
    openCloseChat();
}