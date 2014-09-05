<?php
class TriForce_FreteReal_Model_Observer {
	public function salvarDadosFrete($observer) {
		$order = $observer->getEvent()->getOrder();
		$orderId = $order->getId();
		$resource = Mage::getSingleton('core/resource');
            $connect = $resource->getConnection("core_write");

		if (isset($_SESSION['fretereal']) && isset($_SESSION['fretereal']['calculo'])) {
                  $calculo = $_SESSION['fretereal']['calculo'];
                  $token = $calculo['token'];
                  $caixas = "Token do cálculo: " . $token . "<br/>";

                  foreach ($calculo['caixas'] as $key => $value) {
                  	$caixas .= "Caixa #" . ($key + 1) . ":<br/>";
                  	$caixas .= "Comprimento: ".number_format($value['c'],2)."cm<br/>";
                  	$caixas .= "Largura: ".number_format($value['l'],2)."cm<br/>";
                  	$caixas .= "Altura: ".number_format($value['a'],2)."cm<br/>";
                  	$caixas .= "Peso: ".number_format($value['peso'],2,",",".")."kg<br/>";
                  	$caixas .= "Valor: R$".number_format($value['valor'],2,",",".")."<br/>";
                  	$caixas .= "Produtos da caixa: <br/>";
                  	foreach ($value['produto'] as $pNome => $pQtd) {
                  		$caixas .= $pQtd."x ".$pNome."<br/>";
                  	}

                  	if (($key+1) < count($calculo['caixas'])) {
                  		$caixas .= "<hr/>";
                  	}
                  }
                  
                  // Limpa a variável da sessão
                  $_SESSION['fretereal'] = false;

                  // Atualiza a compra
                  $query = "UPDATE sales_flat_order SET fretereal_token = '".$token."', fretereal_caixas = '".$caixas."' WHERE entity_id = '".(int)$orderId."';";
                  $connect->query($query);

                  // Salva o comentário na compra:
                  $order->addStatusHistoryComment($caixas)
                        ->setIsVisibleOnFront(false)
                        ->setIsCustomerNotified(false);
	      }

		return true;
	}
}
?>