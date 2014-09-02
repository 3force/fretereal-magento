<?php
class TriForce_FreteReal_Model_Source_Methods {    
	public function toOptionArray() {

		$client_id = Mage::getStoreConfig('carriers/triforce_fretereal/client_id');
        $client_secret = Mage::getStoreConfig('carriers/triforce_fretereal/client_secret');

        if ($client_id == "" || $client_secret == "") {
            return array(
            	array(
	            	'label' => "Frete Real",
	            	'value' => array(
	            		array('value' => '0', 'label' => "Comple os dados de acesso da API")
	        		)
        		)
        	);
        } else {
        	if (isset($_SESSION['fretereal']) && isset($_SESSION['fretereal']['fretes'])) {
	        	return $_SESSION['fretereal']['fretes'];
	        }

        	$dadosParaAPI = array(
        		'client_id' => $client_id,
        		'client_secret' => $client_secret
    		);

        	$caminhoUrl = "https://fretereal.com/oauth/";
            $caminhoApi = $caminhoUrl . "action/getFretes";
            $caminhoToken = $caminhoUrl . "action/request_token";

            $curl = curl_init($caminhoToken);
            curl_setopt($curl, CURLOPT_POST, true);
            curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl, CURLOPT_POSTFIELDS, array(
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'grant_type' => 'client_credentials'
                )
            );

            curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
            $retToken = curl_exec($curl);
            $auth = json_decode($retToken);
            $access_key = $auth->access_token;

            $curl2 = curl_init($caminhoApi . "?access_token=" . $access_key);
            curl_setopt($curl2, CURLOPT_POST, true);
            curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($curl2, CURLOPT_POSTFIELDS, http_build_query($dadosParaAPI));
            curl_setopt($curl2, CURLOPT_RETURNTRANSFER, 1);
            $ret = curl_exec($curl2);
            $ret = json_decode($ret, true);

            if ($ret['status'] == 1) {
            	$return = array(
            		array(
		            	'label' => "Frete Real",
		            	'value' => array()
	            	)
	        	);

	        	foreach ($ret['fretes'] as $key => $value) {
	        		$return[0]['value'][] = array('value' => $value['codfrete'], 'label' => $value['descricao']);
	        	}

	        	$_SESSION['fretereal']['fretes'] = $return;
	        	return $return;
            } else {
            	return array(
	            	array(
		            	'label' => "Frete Real",
		            	'value' => array(
		            		array('value' => '0', 'label' => "Comple os dados de acesso da API")
		        		)
	        		)
	        	);
            }
		}
	}
}