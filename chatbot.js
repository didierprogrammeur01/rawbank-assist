const form = document.getElementById("chatForm");
const input = document.getElementById("messageInput");
const chat = document.getElementById("chatMessages");
function ajouterMessage(type, texte)
{
    let div=document.createElement("div");
    div.className=type+"-message";
    div.innerHTML='<div class="message-box">'+texte+'</div>';
    chat.appendChild(div);
    chat.scrollTop=chat.scrollHeight;
}
function envoyerQuestion(question)
{
    input.value=question;
    form.dispatchEvent(new Event("submit"));
}
form.addEventListener("submit",function(e){
    e.preventDefault();
    let message=input.value.trim();
    if(message=="")
        return;
    ajouterMessage("user",message);
    input.value="";
    let actions=document.getElementById("quick-actions");
    if(actions)
        actions.style.display="none";
    let attente=document.createElement("div");
    attente.className="bot-message";
    attente.id="typing";
    attente.innerHTML=
    '<div class="message-box"> RawBank Assist écrit...</div>';
    chat.appendChild(attente);
    chat.scrollTop=chat.scrollHeight;
    fetch("envoyer_message.php",{
        method:"POST",
        headers:{
            "Content-Type":"application/x-www-form-urlencoded"
        },
        body:"message="+encodeURIComponent(message)
    })
    .then(response=>{
        if(!response.ok)
            throw new Error("Erreur HTTP");
        return response.text();
    })
    .then(data=>{
        document.getElementById("typing").remove();
        console.log(data);
        ajouterMessage("bot",data);
    })
    .catch(()=>{
        document.getElementById("typing").remove();
        ajouterMessage("bot"," Une erreur est survenue.");
    });
});