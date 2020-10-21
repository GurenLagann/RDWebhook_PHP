<?php

class Requisicao{

    // URL com a instância do Sugar
    private $instance_url = 'https://lftm.sugarondemand.com/rest/v11/';

    /**
     *  Método POST para autenticação no SugarCRM, que devolve o token de acesso.
     * 
     */
    public function autentica(){

        // Endpoint de autenticação
        $url = $this->instance_url . 'oauth2/token';

        // Configura os parâmetros necessários para autenticação
        $auth = array(
            'grant_type' => 'password',
            'client_id' => 'sugar',
            'username' => 'api.lftm',
            'password' => 'Lftm@api2018',
            'platform' => 'api_rd'
        );

        // Inicia e configura as opções do CURL
        $auth_request = curl_init($url);
        curl_setopt($auth_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($auth_request, CURLOPT_HEADER, false);
        curl_setopt($auth_request, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($auth_request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($auth_request, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($auth_request, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json"
        ));

    // Transforma os parâmetros em JSON
        $json_params = json_encode($auth);
        curl_setopt($auth_request, CURLOPT_POSTFIELDS, $json_params);

        // Execução do CURL
        $output = curl_exec($auth_request);
        curl_close($auth_request);

        // Pega o JSON resposta da requisição em um array
        $json = json_decode($output, true);

        // Retorna o token de acesso
        return $json['access_token'];
    }

    /**
     * Método que verifica se um lead já está cadastrado no SugarCRM.
     * 
     * @param string $email Email do lead, utilizado no SugarCRM como chave primária.
     * @param string $token Token de autorização.
     */
    public function isLead($email, $token){

        $url = $this->instance_url . 'Leads?filter[0][email]=' . $email;

        $curl_request = curl_init($url);
        curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl_request, CURLOPT_HEADER, false);
        curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "oauth-token: {$token}"
        ));

        //execute request
        $curl_response = curl_exec($curl_request);
        //decode json
        $record = json_decode($curl_response, true);

        curl_close($curl_request);

        if($record['records'] == null){
            return false;
        }else{
            return true;
        }


    }

    /**
     * Método que verifica se um lead já está cadastrado no SugarCRM.
     * 
     * @param string $email Email do lead, utilizado no SugarCRM como chave primária.
     * @param string $token Token de autorização.
    */
    public function isCliente($email, $token){

        $url = $this->instance_url . 'Contacts?filter[0][email]=' . $email;

        $curl_request = curl_init($url);
        curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl_request, CURLOPT_HEADER, false);
        curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "oauth-token: {$token}"
        ));

        //execute request
        $curl_response = curl_exec($curl_request);
        //decode json
        $record = json_decode($curl_response, true);

        curl_close($curl_request);

        if($record['records'] == null){
            return false;
        }else{
            return true;
        }


    }

    /*
        Método para criar um novo lead no SugarCRM, através de um método POST.

        Recebe string no formato JSON com as informações do lead.
    */
    public function createLead($json_lead, $token){

        // Monta a url do endpoint para cadastrar leads
        $url = $this->instance_url . 'Leads';

        // Inicia e configura as opções do CURL
        $curl_request = curl_init($url);
        curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
        curl_setopt($curl_request, CURLOPT_HEADER, false);
        curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
        curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
            "Content-Type: application/json",
            "oauth-token: {$token}"
        ));
        curl_setopt($curl_request, CURLOPT_POSTFIELDS, $json_lead);

        // Executa a requisição
        $curl_response = curl_exec($curl_request);
        curl_close($curl_request);
    }
}

