<?php
class TriForce_FreteReal_Model_Carrier extends Mage_Shipping_Model_Carrier_Abstract implements Mage_Shipping_Model_Carrier_Interface {
    protected $_code = 'triforce_fretereal';

    public function collectRates(Mage_Shipping_Model_Rate_Request $request) {
        $result = Mage::getModel('shipping/rate_result');
        try {
            if (Mage::getConfig()->getModuleConfig('TriForce_FreteReal')->is('active', 'true') !== true) {
                return false;
            }

            // Dados de acesso a API do Frete Real
            $client_id = Mage::getStoreConfig('carriers/triforce_fretereal/client_id');
            $client_secret = Mage::getStoreConfig('carriers/triforce_fretereal/client_secret');
            $mao_propria = Mage::getStoreConfig('carriers/triforce_fretereal/mao_propria');
            $aviso_recebimento = Mage::getStoreConfig('carriers/triforce_fretereal/aviso_recebimento');
            $valor_declarado = Mage::getStoreConfig('carriers/triforce_fretereal/valor_declarado');
            $extra_days = Mage::getStoreConfig('carriers/triforce_fretereal/extra_days');

            if ($client_id == "" || $client_secret == "") {
                return false;
            }

            $fretes = Mage::getStoreConfig('carriers/triforce_fretereal/shipping_methods');

            if ($fretes == "") {
                return false;
            }

            $cart = Mage::getModel('checkout/cart')->getQuote();
            $hash = "";

            $shippingAddress = $cart->getShippingAddress();
            $zip = $shippingAddress->getPostcode();
            $dadosParaAPI = array(
                'client_id' => $client_id,
                'client_secret' => $client_secret,
                'cep' => $zip,
                'forma_envio' => $fretes,
                'mao_propria' => ($mao_propria ? "S" : "N"),
                'aviso_recebimento' => ($aviso_recebimento ? "S" : "N"),
                'produtos' => array()
            );
            $hash .= $zip;

            foreach ($cart->getAllVisibleItems() as $key => $item) {
                // Dados dos produtos dentro do carrinho
                $product_sku = $item->getProduct()->getSku();
                $product_name = $item->getProduct()->getName();
                $product_price = $item->getProduct()->getPrice();
                $product_weight = $item->getProduct()->getWeight();
                $product_qtd = $item->getQty();

                $hash .= $product_sku.$product_qtd;

                $_product= Mage::getSingleton('catalog/product')->load($item->getProductId());
                $product_c = $_product->getResource()->getAttribute('package_length')->getFrontend()->getValue($_product);
                $product_l = $_product->getResource()->getAttribute('package_width')->getFrontend()->getValue($_product);
                $product_a = $_product->getResource()->getAttribute('package_height')->getFrontend()->getValue($_product);

                $dadosParaAPI['produtos'][] = array(
                    'nome' => $product_name,
                    'comprimento' => $product_c,
                    'largura' => $product_l,
                    'altura' => $product_a,
                    'peso' => $product_weight,
                    'qtd' => $product_qtd
                );

                if ($valor_declarado) {
                    $dadosParaAPI['produtos'][$key]['valor'] = $product_price;
                }
            }

            if (isset($_SESSION['fretereal']) && $_SESSION['fretereal']['hash'] == $hash) {
                $ret = $_SESSION['fretereal']['calculo'];
            } else {
                $caminhoUrl = "https://fretereal.com/oauth/";
                $caminhoApi = $caminhoUrl . "action/api";
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

                $_SESSION['fretereal']['hash'] = $hash;
                $_SESSION['fretereal']['calculo'] = $ret;
                $_SESSION['fretereal']['calculo']['token'] = $access_key;
            }

            if ($ret['status'] == 1) {
                $alertas = array();
                foreach ($ret['fretes']['correios'] as $key => $value) {
                    if (isset($value['alerta']) && !in_array($value['alerta'], $alertas) && $value['alerta'] != "") {
                        Mage::getSingleton('core/session')->addNotice($value['alerta']);
                        $alertas[] = $value['alerta'];
                    }
                    $method = Mage::getModel('shipping/rate_result_method');
                    $method->setCarrier("triforce_fretereal")
                                ->setCarrierTitle(Mage::getStoreConfig("carrier/triforce_fretereal/title"))
                                ->setMethod($value['tipo_frete'])
                                ->setMethodTitle(self::getTitulo($value['tipo_frete']) . " (Prazo: ".($extra_days > 0 ? ($value['prazo'] + $extra_days) : $value['prazo'])." dias)")
                                ->setCost($value['valor'])
                                ->setPrice($value['valor']);
                    $result->append($method);
                }
            }
        } catch (Exception $e) {
            $this->_appendError($result, $e->getMessage());
            return $result;
        }

        return $result;
    }

    public function getAllowedMethods() {
        return array('triforce_fretereal'=>'Frete Real');
    }

    public function getTitulo($code) {
        $options = Mage::getSingleton('triforce_fretereal/source_methods')->toOptionArray();       
        
        foreach($options as $option) {
            if(is_array($option['value'])) {
            foreach($option['value'] as $method) {
                if($method['value'] == $code) {
                return $method['label'];
                }
            }
            } else {
            if($option['value'] == $code) {
                return $option['label'];
            }
            }
        }
        
        return false;
    }
}