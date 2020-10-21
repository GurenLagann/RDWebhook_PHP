<?php

require('RequisicaoDev.php');
require('Tratamento.php');



$json = json_decode(file_get_contents("php://input"), true);



$req = new Requisicao();
$token = $req->autentica();



$email = $json['leads']['0']['email'];
$educacao =  $json['leads']['0']['custom_fields']['Interessado em'];
$verif = "Escola de Investimentos";


$lead = Tratamento::trataLead($json);



$json_lead = json_encode($lead, JSON_UNESCAPED_SLASHES);



if($req->isLead($email, $token) == false && $req->isCliente($email, $token) == false){

        $req->createLead($json_lead, $token);

}

if(strcasecmp($educacao, $verif) == 0){
        if($req->isLead($email, $token) == true && $req ->isCliente($email, $token) == false){
                $req->createOportunityLead($email, $token);
        }
        elseif($req->isLead($email, $token) == false && $req->isCliente($email, $token) == true){
                $req->createOportunityContacts($email, $token);
        }
        else {
                $req->createOportunityNew($json, $token);
        }
}





?>

