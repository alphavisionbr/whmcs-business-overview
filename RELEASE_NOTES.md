# Alphavision WHMCS Business Overview 1.0.5

## Atualização

Substitua modules/ na raiz do WHMCS sem desativar o addon. Recarregue o dashboard por completo antes de testar Atualizar. Como o identificador interno do widget mudou, pode ser necessário habilitá-lo novamente nas opções do dashboard e ajustar sua posição. Configurações do addon e permissões são preservadas.

## Indicadores

Clientes ativos é o primeiro item de Comercial. Serviços e operação segue: Hospedagem/Revendas ativas, Outros serviços ativos, Domínios ativos, Serviços suspensos, Serviços pendentes, Cancelamentos pendentes e Domínios próximos do vencimento.

## Atualização nativa

O widget usa classe global exclusiva para o identificador nativo. getData coleta os dados novamente, sem cache da sessão; generateOutput somente apresenta a coleta recebida e revalida o acesso. Falhas capturáveis de coleta geram uma mensagem de indisponibilidade.

Essas mudanças corrigem fragilidades da integração. Sem a resposta HTTP da instalação original, a causa exata do carregamento infinito ainda não foi comprovada. Se persistir, a resposta da requisição Atualizar na aba Rede do navegador e o erro PHP correspondente serão necessários para identificar falhas de servidor ou do dashboard.

## Validação

59 verificações locais em PHP 8.3.6 passaram. Incluem identidade do widget, dados alterados entre duas coletas, renderização sem repetir API, mensagem de falha e ordem dos grupos. O adaptador de WHMCS é simulado, portanto o AJAX real continua pendente de homologação.
