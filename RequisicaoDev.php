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
                    "grant_type" => "password",
                    //client id - default is sugar. 
                    //It is recommended to create your own in Admin > OAuth Keys
                    "client_id" => "sugar", 
                    "client_secret" => "",
                    'username' => 'api.lftm',
                    'password' => 'Suporte@lftm2020',
                    //platform type - default is base.
                    //It is recommend to change the platform to a custom name such as "custom_api" to avoid authentication conflicts.
                    "platform" => "custom_api" 
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
                $sugar = $record;
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
                $sugar = $record;
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

        public function createOportunityNew($json, $token){
            $url = $this->instance_url . 'Opportunities/';

            $record = array(
                'name' => 'Contado Escola - '. $json['leads']['0']['name'],
                'created_by' => '2512a2b8-1400-11e9-8698-022c40d974e6',
                'created_by_name' => 'API Lifetime',
                'description' => 'Lead enteresssado em cursos da escola de investimento',
                'opportunity_type'=>'Escola',
                'date_closed' => '2020-12-31',
                'assigned_user_id' => 'dde5fba0-0860-6a59-ad4f-55d89c55f7f5',
                'assigned_user_name' => 'Marcello Giutini Popoff',
                'lftm_quantidade_lote_c' => 1                  
            );
            

            $curl_request = curl_init($url);
            curl_setopt($curl_request, CURLOPT_POST, 1);
            curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($curl_request, CURLOPT_HEADER, false);
            curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
            curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "oauth-token: {$token}"
            ));

            //convert arguments to json
            $json_arguments = json_encode($record);
            curl_setopt($curl_request, CURLOPT_POSTFIELDS, $json_arguments);
            //execute request
            $curl_response = curl_exec($curl_request);
            //decode json
            $noteRecord = json_decode($curl_response);

            //display the created record
            echo "Created Record: ". $noteRecord->id;

            curl_close($curl_request);

            //Add An Attachment to the Note
            $url = $this->instance_url . "/Oportunities/{$noteRecord->id}/file/filename";

            $file_arguments = array(
                "format" => "sugar-html-json",
                "delete_if_fails" => true,
                "oauth_token" => $token,
            );

            $curl_request = curl_init($url);
            curl_setopt($curl_request, CURLOPT_POST, 1);
            curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($curl_request, CURLOPT_HEADER, false);
            curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
            //Do NOT set Content Type Header to JSON
            curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
                "oauth-token: {$token}"
            ));

            curl_setopt($curl_request, CURLOPT_POSTFIELDS, $file_arguments);
            //execute request
            $curl_response = curl_exec($curl_request);
            //decode json
            $noteRecord = json_decode($curl_response);
            //print Note with attachment details
            echo $noteRecord;
            print_r($noteRecord);

            curl_close($curl_request);
        }

        public function createOportunityLead($email, $token){
            
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
            
            $url = $this->instance_url . 'Opportunities/';

            $record = array(
                'name' => 'Contado Escola - '. $record['records']['0']['name'],
                'created_by' => '2512a2b8-1400-11e9-8698-022c40d974e6',
                'created_by_name' => 'API Lifetime',
                'description' => 'Lead interesssado em cursos da escola de investimento.',
                'opportunity_type'=>'Escola',
                'date_closed' => '2020-12-31',
                'assigned_user_id' => 'dde5fba0-0860-6a59-ad4f-55d89c55f7f5',
                'assigned_user_name' => 'Marcello Giutini Popoff',
                'lftm_quantidade_lote_c' => 1,
                'leads_opportunities_1_name' => $record['records']['0']['name'],
                /*'leads_opportunities_1' => array(
                    array(
                       'full_name' => $record['records']['0']['name'],
                       'id' => $record['records']['0']['id'] 
                    )
                ),*/
                'leads_opportunities_1leads_ida' => $record['records']['0']['id'], 
            );
            echo $record;

            $curl_request = curl_init($url);
            curl_setopt($curl_request, CURLOPT_POST, 1);
            curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($curl_request, CURLOPT_HEADER, false);
            curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
            curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "oauth-token: {$token}"
            ));

            //convert arguments to json
            $json_arguments = json_encode($record);
            curl_setopt($curl_request, CURLOPT_POSTFIELDS, $json_arguments);
            //execute request
            $curl_response = curl_exec($curl_request);
            //decode json
            $noteRecord = json_decode($curl_response);

            //display the created record
            echo "Created Record: ". $noteRecord->id;

            curl_close($curl_request);

            //Add An Attachment to the Note
            $url = $this->instance_url . "/Oportunities/{$noteRecord->id}/file/filename";

            $file_arguments = array(
                "format" => "sugar-html-json",
                "delete_if_fails" => true,
                "oauth_token" => $token,
            );

            $curl_request = curl_init($url);
            curl_setopt($curl_request, CURLOPT_POST, 1);
            curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($curl_request, CURLOPT_HEADER, false);
            curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
            //Do NOT set Content Type Header to JSON
            curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
                "oauth-token: {$token}"
            ));

            curl_setopt($curl_request, CURLOPT_POSTFIELDS, $file_arguments);
            //execute request
            $curl_response = curl_exec($curl_request);
            //decode json
            $noteRecord = json_decode($curl_response);
            //print Note with attachment details
            echo $noteRecord;
            print_r($noteRecord);

            curl_close($curl_request);
        }

        public function createOportunityContacts($email, $token){
            
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
            
            $url = $this->instance_url . 'Opportunities/';

            $record = array(
                'name' => 'Contado Escola - '. $record['records']['0']['name'],
                'created_by' => '2512a2b8-1400-11e9-8698-022c40d974e6',
                'created_by_name' => 'API Lifetime',
                'description' => 'Cliente interesssado em cursos da escola de investimento. ',
                'opportunity_type'=>'Escola',
                'date_closed' => '2020-12-31',
                'assigned_user_id' => 'dde5fba0-0860-6a59-ad4f-55d89c55f7f5',
                'assigned_user_name' => 'Marcello Giutini Popoff',
                'lftm_quantidade_lote_c' => 1,
                /*'contacts_opportunities_1_name' => $record['records']['0']['name'],
                'contacts_opportunities_1' => array(
                    array(
                       'full_name' => $record['records']['0']['name'],
                       'id' => $record['records']['0']['id'] 
                    )
                ),*/
                'contacts_opportunities_1contacts_ida' => $record['records']['0']['id'],
            );
            

            $curl_request = curl_init($url);
            curl_setopt($curl_request, CURLOPT_POST, 1);
            curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($curl_request, CURLOPT_HEADER, false);
            curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
            curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
                "Content-Type: application/json",
                "oauth-token: {$token}"
            ));

            //convert arguments to json
            $json_arguments = json_encode($record);
            curl_setopt($curl_request, CURLOPT_POSTFIELDS, $json_arguments);
            //execute request
            $curl_response = curl_exec($curl_request);
            //decode json
            $noteRecord = json_decode($curl_response);

            //display the created record
            echo "Created Record: ". $noteRecord->id;

            curl_close($curl_request);

            //Add An Attachment to the Note
            $url = $this->instance_url . "/Oportunities/{$noteRecord->id}/file/filename";

            $file_arguments = array(
                "format" => "sugar-html-json",
                "delete_if_fails" => true,
                "oauth_token" => $token,
            );

            $curl_request = curl_init($url);
            curl_setopt($curl_request, CURLOPT_POST, 1);
            curl_setopt($curl_request, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0);
            curl_setopt($curl_request, CURLOPT_HEADER, false);
            curl_setopt($curl_request, CURLOPT_SSL_VERIFYPEER, 0);
            curl_setopt($curl_request, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl_request, CURLOPT_FOLLOWLOCATION, 0);
            //Do NOT set Content Type Header to JSON
            curl_setopt($curl_request, CURLOPT_HTTPHEADER, array(
                "oauth-token: {$token}"
            ));

            curl_setopt($curl_request, CURLOPT_POSTFIELDS, $file_arguments);
            //execute request
            $curl_response = curl_exec($curl_request);
            //decode json
            $noteRecord = json_decode($curl_response);
            //print Note with attachment details
            echo $noteRecord;
            print_r($noteRecord);

            curl_close($curl_request);
        }

    }