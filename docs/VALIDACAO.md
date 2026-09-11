# Validação local

## Versão 1.0.5

59 verificações em PHP 8.3.6 aprovadas. Sintaxe dos 12 arquivos PHP verificada. Inclui nova coleta após alteração dos dados, separação entre coleta e apresentação, identificador global exclusivo, falha explícita e ordem dos indicadores. Classe base WHMCS simulada: o teste não reproduz o roteamento AJAX nem o JavaScript do dashboard.


## Versão 1.0.4

51 verificações locais aprovadas em PHP 8.3.6. Sintaxe dos 11 arquivos PHP conferida. Inclui soma de hospedagem/revenda, ausência do total redundante e remoção do período e rodapé do widget. Conferência visual e interação nativa em WHMCS pendentes.


## Versão 1.0.3

47 verificações em PHP 8.3.6 aprovadas, incluindo precedência de configuração nativa, checkbox desmarcado, preservação de permissões e valores antigos. Sintaxe dos 11 arquivos PHP validada. O formulário nativo e o posicionamento do widget requerem homologação no WHMCS.


## Versão 1.0.2

41 verificações passaram em PHP 8.3.6 com adaptadores SQLite/WHMCS. Sintaxe dos 10 arquivos PHP verificada. Foram geradas as saídas HTML do widget, visão geral, configurações e diagnóstico com dados fictícios. A conferência renderizada não foi realizada: a política do navegador rejeitou a abertura dos arquivos locais. A opção compacta usa CSS para ocultar descrições; a interação do seletor ainda requer homologação no navegador.


## Validação da versão 1.0.1

Sintaxe dos 10 arquivos PHP verificada em PHP 8.3.6. As 41 verificações locais passaram com SQLite e contratos simulados, incluindo addon sempre completo e os dois layouts do widget. As verificações em PHP 8.2 abaixo se referem à versão 1.0.0.

## Resultado da versão 1.0.0

- Sintaxe dos 10 arquivos PHP de execução e do teste verificada em PHP 8.2.33 e PHP 8.3.33 via PHP-WASM.
- 38 verificações de regressão passaram em cada versão, usando SQLite em memória e adaptadores de teste para os contratos WHMCS/Capsule.
- O teste executa as classes reais de classificação, autorização, coleta e apresentação. Apenas os serviços fornecidos pelo WHMCS são simulados.
- GetStats é simulado: foram verificados tratamento dos valores, chamada única por coleta e bloqueio sem permissão. Não foi validado o resultado real dessa API.
- Testados períodos, orçamentos, serviços sem produto, precedência de classificação, cancelamentos duplicados, janela de expiração, suporte por departamento e status personalizado.
- Testados crédito, pagamentos parciais, saídas/reembolsos, excesso de pagamento, atraso, separação de moedas, revogação de perfil/permissão e alteração de departamentos com cache existente.
- Testados escape de HTML, apresentação monetária e distinção entre zero e indisponibilidade.

## Conferência visual da versão 1.0.0

A saída HTML real do widget foi renderizada com dados fictícios em navegador Chromium, em larguras de 1120 e 390 pixels, sem transbordamento horizontal. A tela de configurações foi renderizada e inspecionada em 1120 pixels. Isso verifica a composição isolada; a combinação com o CSS e os componentes do tema Blend requer homologação no WHMCS.

## Limite visual da versão 1.0.1

A saída HTML foi gerada com dados fictícios, mas a captura em navegador não pôde ser repetida nesta revisão porque o navegador local estava indisponível e o download expirou. O layout responsivo, o menu no Blend e o bloco de apoio precisam de conferência na homologação.

## Pacote de instalação

Os testes e adaptadores usados na validação local não integram o ZIP de instalação. O pacote contém somente o módulo e a documentação de uso e homologação.

## Limites

PHP-WASM não é PHP-FPM do servidor. SQLite com adaptador de consultas não substitui o Capsule, MySQL/MariaDB ou as classes reais do WHMCS. Os testes locais não comprovam instalação, hooks, tokens nativos, todas as rotas administrativas ou desempenho em bases grandes.

A homologação em WHMCS 9.x, Admin Blend e banco real continua pendente. A conciliação dos valores financeiros é obrigatória antes de usar o painel para decisões. Consulte HOMOLOGACAO.md.
