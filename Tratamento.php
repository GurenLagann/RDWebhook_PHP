<?php

class Tratamento{

    public static function trataLead($json){

        $email = $json['leads']['0']['email'];
        $name = $json['leads']['0']['name'];
        $url = $json['leads']['0']['public_url'];
        $score = $json['leads']['0']['interest'];
        $estado = $json['leads']['0']['state'];
        $cidade = $json['leads']['0']['city'];
        $source = $json['leads']['0']['first_conversion']['conversion_origin']['source'];
        $channel = $json['leads']['0']['first_conversion']['conversion_origin']['channel'];
        $interessado = $json['leads']['0']['custom_fields']['Interessado em'];
	    $indentificador = $json['leads']['0']['last_conversion']['content']['identificador'];
        $faixa = $json['leads']['0']['custom_fields']['Faixa de investimento OK'];
        $msg = $json['leads']['0']['custom_fields']['Mensagem'];
	    $result = $json['leads']['0']['last_conversion']['content']['cf_resultado_do_perfil'];


        $mobile = $json['leads']['0']['personal_phone'];
        if($mobile == null) $mobile = $json['leads']['0']['mobile_phone'];

        $tags = $json['leads']['0']['tags'];

        $contato = '';

        foreach ($tags as $key => $value) {
            if($value == 'solicitação de contato'){
                $contato = 'Solicitou Contato | ';
            }
        }

        $detalhe = $contato . $url;

        $ate300k = "Até R$100 mil";
        $ate300k = "De R$100 mil a R$300 mil";
        $f300a500 = "De R$300 mil a R$500 mil";
        $f500a1000 = "De R$500 mil a R$1 milhão";
        $f1a10MM = "Acima de R$1 milhão";
	    $cambio = "câmbio";
	    $beijaflor= "Beija-Flores Solidários";
	    $ident = "Teste perfil de Investimento";
	    $desc = "";
   
        if (strcasecmp($faixa, $ate300k) == 0){
            $faixa = "ate300";
        }

        if (strcasecmp($faixa, $f300a500) == 0){
            $faixa = "300_1000";
        }
        
        if (strcasecmp($faixa, $f500a1000) == 0){
            $faixa = "300_1000";
        }
        
        if (strcasecmp($faixa, $f1a10MM) == 0){
            $faixa = "1_10MM";
        }

	    if (strcasecmp($interessado, $cambio) == 0){
		    $interessado = "cambio";
	    }
    
	    if (strcasecmp($interessado, $beijaflor) == 0){
		    $interessado = "beijaflor";
	    }

	    if (strcasecmp($indentificador, $ident) == 0){
		    $interessado = "caracteristica";
		    $desc = "Perfil do Lead:  $result";
	    }

        $lead = array(
            'first_name' => $name,
            'email' => array(
                array(
                    'email_address' => $email,
                    'primary_address' => true
                )
            ),
            'lftm_it_rd_station_c' => $channel . ' | ' . $source,
            'phone_mobile' => $mobile,
            'lftm_lead_score_interesse_rd_c' => $score,
            'lead_source' => 'rdstation',
            'lead_source_description_new_c' => $interessado,
            'lftm_ti_msg_fc_c' => $msg,
            'lftm_ti_url_rdstation_c' => $detalhe,
            'description' => $desc,
            'lftm_estimativa_pelo_assesso_c' => $faixa,
            'primary_address_city' => $cidade,
            'primary_address_state' => $estado
        );

        return $lead;
    }

}

?>

