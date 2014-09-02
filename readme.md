#Frete Real - Plugin Para Magento - v.0.0.1
Este módulo contempla a integração completa do Frete Real para lojas que utilizam a plataforma magento.

##Funcionalidades
* Busca em sua conta do Frete Real os métodos de frete que sua empresa trabalha
* Integrado para declarar os valores (caso sua empresa utilize fretes aéreos, com seguro, etc..)
* Integrado para solicitar a cotação de: Aviso de Recebimento e Mão Própria
* Exibe informações recebidas pelos correios, ex: Áreas de Risco

##Instalação
Para instalar este módulo, efetue seu cadastro no [*Frete Real](https://fretereal.com) para obter suas credenciais para utilizar a API e em seguida efeute o seguinte procedimento:

1. Baixe este arquivo .zip
2. Envie para seu servidor por FTP a pasta "app"
3. Vá até o painel administrativo e acesse "System -> Configuration -> Shipping Methods" e habilite o Frete Real
4. Informe as suas credenciais (client_id e client_secret) e salve as configurações para o sistema carregar os fretes aceitos pela sua empresa
5. Selecione os fretes que deseja utilizar na loja
6. Configure os demais dados
7. Após a configuração, seus produtos possuirão uma nova "aba" de configuração "Dimensions", nesta aba informe as medidas dos produtos.

##Entendendo como funciona
Quando um cliente adiciona um produto no carrinho, e informa seu "ZIP CODE" (CEP), o sistema verifica as configurações e envia para a API as informações do carrinho.
Quando o Frete Real retorna as informações, armazenamos na $_SESSION uma identificação para este frete e também os valores, assim você não consome cálculos repetidos enquanto o cliente não efetua a compra.