# Arquitetura da versão 1.0.1

## Decisão

Addon Module para configuração e diagnóstico, mais widget registrado por AdminHomeWidgets no hooks.php do próprio addon. Classes isoladas no namespace Alphavision\BusinessOverview. Sem cópia para modules/widgets, sem hook global adicional, sem cron e sem alterações em arquivos nativos.

## Fontes e confiabilidade

GetStats oferece receita, pedidos, tickets, cancelamentos e orçamentos. Nesta versão ele é usado somente para receita, depois de autorizar sua visualização. Não fornece todos os recortes necessários. As demais contagens usam consultas parametrizadas pelo Capsule, com agregação no banco. Isso permite aplicar o período, departamentos e classificação exatos sem percorrer resultados paginados de várias APIs.

Classes nativas: Admin para autenticação, permissões e departamentos; AbstractWidget para o widget; Capsule para consultas; Config\Setting para versão. check_token/generate_token protegem formulários.

Não é usado GetInvoice por fatura no dashboard, porque criaria uma chamada por registro. Saldos são calculados em uma consulta agregada: pré-agrega transações por fatura, desconta crédito, limita o saldo a zero por fatura e agrupa por moeda do cliente. A fórmula é uma decisão explícita da implementação e precisa ser conciliada na instalação alvo.

## Classificação

Produto explicitamente marcado prevalece sobre grupo, que prevalece sobre tipo nativo. Mesmo produto ou grupo nas duas categorias é rejeitado ao salvar. Tipos nativos hostingaccount/reselleraccount fornecem um padrão inicial. Outros tipos e produtos ausentes ficam em Outros. Assim o total das três categorias iguala Serviços ativos.

## Status

Tickets ativos/aguardando resposta são descobertos em tblticketstatuses por showactive/showawaiting. Progresso e análise aceitam seleção dos status existentes, inclusive personalizados. Status nativos de serviços Active/Suspended/Pending são usados como estados operacionais estáveis. Pedidos completos usam Active explicitamente; não equiparam status personalizados a vendas concluídas.

## Segurança e cache

Access Control nativo permite entrada. Cada métrica verifica a permissão para sua área. Monetários exigem habilitação e perfil explícito. Tickets usam os departamentos obtidos do objeto Admin. Sem departamentos, nenhuma consulta global de tickets é feita. View Flagged Tickets é exigida adicionalmente para sinalizados.

O cache é da sessão e inclui administrador, perfil, departamentos, lista permitida, preferências, versão, data e fuso. As permissões são reavaliadas antes de consultar o cache e antes de renderizar. Mudanças em preferências invalidam o contexto de outras sessões no próximo acesso. Prazo configurável de 0/30/60/120/300 segundos. Não há cache global com dados sensíveis.

A única tabela própria guarda JSON de preferências, sem dados individuais. O log nativo registra somente a atualização de configurações, sem valores comerciais ou SQL. Erros de indicador são exibidos como Indisponível. Não há endpoint público ou conexão externa.

## Escala e interface

Quatro grupos em duas colunas responsivas, números tabulares e realce âmbar para pendências. Sem zebra nas configurações, cores e classes limitadas ao prefixo avbo. Configurações globais para evitar complexidade de preferências pessoais na primeira versão. Consultas de serviços e suporte são compartilhadas dentro da coleta. A coleta de receita via GetStats pode ter custo maior por executar estatísticas nativas adicionais.

## Fora desta versão

Comparativos mensais, MRR, tendências, captura histórica da data de aceite, reorganização por arrastar, preferências por administrador, faixas de atraso, incidentes de rede e atualização em segundo plano. WHMCS 8.x e outras versões de PHP dependem de validação futura.

## Referências oficiais consultadas

- [Widgets](https://developers.whmcs.com/advanced/widgets/)
- [GetStats](https://developers.whmcs.com/api-reference/getstats/)
- [Permissões administrativas](https://developers.whmcs.com/api-reference/getadmindetails/)
- [Admin: permissões e departamentos](https://classdocs.whmcs.com/8.13/WHMCS/User/Admin.html)
- [GetInvoice: crédito e saldo](https://developers.whmcs.com/api-reference/getinvoice/)

A documentação pública das classes consultada corresponde à 8.13. Ela orienta os contratos, mas não substitui execução em WHMCS 9.x.
