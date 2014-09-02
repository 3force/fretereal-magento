#Frete Real - Plugin para Magento - v.0.0.1
Este módulo contempla a integração completa do Frete Real para lojas que utilizam a plataforma Magento.

##Funcionalidades
* Busca em sua conta do Frete Real os tipos de frete que sua empresa trabalha
* Integrado para declarar valores (caso sua empresa utilize fretes aéreos, com seguro, etc..)
* Integrado para solicitar a cotação de Aviso de Recebimento e Mão Própria
* Exibe informações recebidas pelos Correios (ex: Áreas de Risco)
* Integrado para calcular, em apenas 1 request, todos os tipos de fretes disponíveis

##Instalação
Para instalar este módulo, cadastre-se no [Frete Real](https://fretereal.com) para obter as credenciais de acesso para utilizar a API. Em seguida efetue o seguinte procedimento:

1. Baixe este arquivo .zip
2. Envie a pasta "app" para seu servidor por FTP
3. Vá até o painel administrativo e acesse "System -> Configuration -> Shipping Methods" e habilite o Frete Real
4. Informe as suas credenciais (client_id e client_secret) e salve as configurações para o sistema carregar os fretes aceitos pela sua empresa
5. Selecione os fretes que deseja utilizar na loja
6. Configure os demais dados
7. Após a configuração, seus produtos apresentarão uma nova "aba" de configuração ("Dimensions"), nesta aba, informe as medidas dos produtos.

##Entendendo como funciona
Quando um cliente adiciona um produto no carrinho, e informa seu "ZIP CODE" (CEP), o sistema verifica as configurações e envia para a API as informações do carrinho.

Quando o Frete Real retorna as informações, armazenamos na $_SESSION uma identificação para este frete, e também os valores; assim você não consome cálculos repetidos enquanto o cliente não efetua a compra.