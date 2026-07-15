<?php
session_start();
include("config/database.php");

if(!isset($_SESSION['user_id']))
{
    exit();
}
$user_id = $_SESSION['user_id'];

if(!isset($_POST['message']))
{
    exit();
}
$message = trim($_POST['message']);

if($message=="")
{
    exit();
}
$sql = mysqli_query($conn,"
SELECT id_conversation
FROM conversations
WHERE id_utilisateur='$user_id'
LIMIT 1");
$row = mysqli_fetch_assoc($sql);
if(!$row)
{
    exit("Conversation introuvable.");
}
$conversation = $row['id_conversation'];
mysqli_query($conn,"
INSERT INTO messages
(id_conversation,expediteur,contenu)
VALUES
('$conversation','client','$message')");
$messageLower = strtolower($message);
$reponse = "";

if(
    strpos($messageLower,"solde")!==false ||
    strpos($messageLower,"argent")!==false ||
    strpos($messageLower,"compte")!==false ||
    strpos($messageLower,"combien")!==false
)
{
    $sql = mysqli_query($conn,"
    SELECT solde
    FROM comptes
    WHERE id_utilisateur='$user_id'");
    $data = mysqli_fetch_assoc($sql);
    if($data)
    {
        $reponse=" Votre solde actuel est de <b>".$data['solde']." USD</b>.";
    }
    else
    {
        $reponse="Aucun compte bancaire trouvé.";
    }
}
elseif(
    strpos($messageLower,"carte")!==false ||
    strpos($messageLower,"visa")!==false ||
    strpos($messageLower,"mastercard")!==false ||
    strpos($messageLower,"numéro de carte")!==false
)
{
    $sql = mysqli_query($conn,"
    SELECT *
    FROM cartes_bancaires cb
    INNER JOIN comptes c
    ON cb.id_compte=c.id_compte
    WHERE c.id_utilisateur='$user_id'");
    $card = mysqli_fetch_assoc($sql);
    if($card)
    {
        $reponse="
        <b>Carte bancaire</b><br><br>

        Type : ".$card['type_carte']."

        <br>Numéro : ".$card['numero_carte']."

        <br>Expiration : ".$card['date_expiration']."

        <br>Statut : ".$card['statut_carte'];
    }
    else
    {
        $reponse="Aucune carte bancaire trouvée.";
    }
}
elseif(
    strpos($messageLower,"historique")!==false ||
    strpos($messageLower,"transaction")!==false ||
    strpos($messageLower,"opération")!==false ||
    strpos($messageLower,"operations")!==false ||
    strpos($messageLower,"relevé")!==false ||
    strpos($messageLower,"mouvement")!==false
)
{
    $sql = mysqli_query($conn,"
    SELECT *
    FROM transactions
    WHERE id_utilisateur='$user_id'
    ORDER BY date_operation DESC
    LIMIT 5");
    $reponse="<b>Vos dernières opérations</b><br><br>";
    while($t=mysqli_fetch_assoc($sql))
    {
        $reponse.="<b>".$t['operation']."</b> : ".$t['montant']." USD<br>";
    }
}
elseif(
    strpos($messageLower,"bonjour")!==false ||
    strpos($messageLower,"salut")!==false ||
    strpos($messageLower,"bonsoir")!==false ||
    strpos($messageLower,"hello")!==false
)
{
    $reponse="Bonjour <b>".$_SESSION['nom']."</b> <br><br>
    Bienvenue sur <b>RawBank Assist</b>.<br><br>
    Comment puis-je vous aider aujourd'hui ?";

}
elseif(strpos($messageLower,"merci")!==false)
{
    $reponse="Avec plaisir ! Je reste à votre disposition.";
}
elseif(
    strpos($messageLower,"au revoir")!==false ||
    strpos($messageLower,"bye")!==false
)
{
    $reponse="Merci d'avoir utilisé RawBank Assist.<br>À bientôt.";
}
elseif(
    strpos($messageLower,"conseiller")!==false ||
    strpos($messageLower,"agent")!==false
)
{
    $reponse=" Vous pouvez contacter un conseiller grâce au bouton <b>Parler à un conseiller</b> situé sous cette conversation.";

}
elseif(
    strpos($messageLower,"aide")!==false ||
    strpos($messageLower,"help")!==false
)
{
    $reponse="
    Je peux vous aider pour :<br><br>
    • Consulter votre solde<br>
    • Voir votre carte bancaire<br>
    • Consulter vos transactions<br>
    • Contacter un conseiller.";

}
elseif(
    strpos($messageLower,"mon nom")!==false ||
    strpos($messageLower,"qui suis")!==false
)
{
    $reponse="Vous êtes connecté sous le nom de <b>".$_SESSION['nom']."</b>.";

}
elseif(
    strpos($messageLower,"ça va")!==false ||
    strpos($messageLower,"ca va")!==false
)
{
    $reponse='Je vais très bien, merci. Comment puis-je vous aider ?';
}
else
{
    $reponse = '
    Je n\'ai pas compris votre demande.<br><br>
    Essayez par exemple :<br><br>
    Quel est mon solde ?<br>
    Afficher ma carte bancaire<br>
    Voir mon historique<br>
    Parler à un conseiller';

}
mysqli_query($conn,"
INSERT INTO messages
(id_conversation,expediteur,contenu)
VALUES
('$conversation','bot','$reponse')");
echo $reponse;
?>