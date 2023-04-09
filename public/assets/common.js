var INDEX = 0;
function addMessage(data, currentId) {


    // close chat
    if (currentId == -2) {
        document.getElementById("texts").innerHTML += "<div style='font-weight:bolder;width: 100%;display: flex;justify-content: center;margin-top: 10px'>"+data.message+"</div><br><button onclick='restartChat()' style='color: white' class='btn btn-info'>Reconnecter</button>";
        document.getElementById("chatForm").style.opacity = "0";
        document.getElementById("userChatInput").disable = true;
        document.getElementById("userChatButton").disable = true;
        return;
    }


    // add new text
    let type = "user";
    if(data.sender == currentId) {
        type = "self";
    }
    INDEX++;
    var str="";
    str += "<div id='cm-msg-"+INDEX+"' class=\"chat-msg "+type+"\">";
    str += "          <span style='display: inline' class=\"msg-avatar\">";
    str += "            <img src='https://cdn.iconscout.com/icon/free/png-256/avatar-370-456322.png'>";
    str += "          </span>";
    str += "          <div class=\"cm-msg-text\">";
    str += data.message;
    str += "          </div>";
    str += "        </div>";
    $(".chat-logs").append(str);
    $("#cm-msg-"+INDEX).hide().fadeIn(300);
    $(".chat-logs").stop().animate({ scrollTop: $(".chat-logs")[0].scrollHeight}, 1000);

}