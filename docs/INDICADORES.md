# Indicadores, permissões e destinos

Atalhos são relativos ao diretório administrativo atual, inclusive quando ele foi renomeado. Links sem recorte exato são explicitados nas definições.

| Indicador | Permissão nativa | Destino | Definição |
|---|---|---|---|
| Pedidos hoje | View Orders | `orders.php` | Pedidos criados hoje, em qualquer status. |
| Pedidos pendentes | View Orders | `orders.php?status=Pending` | Todos os pedidos atualmente pendentes, independentemente da data. |
| Pedidos completos no mês | View Orders | `orders.php?status=Active` | Pedidos criados neste mês e atualmente Active. Não representa a data de aceitação ou pagamento. O atalho abre todos os pedidos ativos. |
| Solicitações de orçamento | Manage Quotes | `quotes.php?validity=Valid` | Orçamentos Draft ou Delivered ainda válidos, incluindo o dia de validade. Não inclui aceitos, perdidos ou encerrados. |
| Novos clientes no mês | List Clients | `clients.php` | Cadastros de clientes criados neste mês. O atalho abre a lista de clientes. |
| Clientes ativos | List Clients | `clients.php?status=Active` | Clientes com status Active. |
| Receita hoje | View Income Totals + perfil monetário | `transactions.php` | Receita retornada pelo GetStats nativo na moeda padrão. Consulte a definição do WHMCS; não é lucro. |
| Receita no mês | View Income Totals + perfil monetário | `transactions.php` | Receita do mês retornada pelo GetStats nativo na moeda padrão. O atalho abre todas as transações. |
| Faturas em aberto | List Invoices | `invoices.php?status=Unpaid` | Faturas Unpaid, incluindo as atrasadas. |
| Valor total em aberto | List Invoices + perfil monetário | `invoices.php?status=Unpaid` | Saldo por moeda: total menos crédito e recebimentos líquidos de saídas; mínimo zero por fatura. |
| Faturas atrasadas | List Invoices | `invoices.php?status=Overdue` | Faturas Unpaid com vencimento anterior a hoje. As que vencem hoje não estão atrasadas. |
| Valor total atrasado | List Invoices + perfil monetário | `invoices.php?status=Overdue` | Saldo das faturas atrasadas separado por moeda. |
| Hospedagem/Revendas ativas | List Services | `clientshostinglist.php?status=Active` | Classificação: produto explícito, grupo explícito, tipo nativo. O atalho abre todos os serviços ativos. |
| Outros serviços ativos | List Services | `clientshostinglist.php?status=Active` | Serviços ativos não classificados como Hospedagem ou Revenda. O atalho abre todos os serviços ativos. |
| Serviços suspensos | List Services | `clientshostinglist.php?status=Suspended` | Serviços com status Suspended. |
| Serviços pendentes | List Services | `clientshostinglist.php?status=Pending` | Serviços com status Pending. |
| Cancelamentos pendentes | View Cancellation Requests | `cancelrequests.php` | Serviços distintos com solicitação de cancelamento, ainda não Cancelled ou Terminated. |
| Domínios ativos | List Domains | `clientsdomainlist.php?status=Active` | Domínios com status Active. |
| Domínios próximos do vencimento | List Domains | `clientsdomainlist.php?status=Active` | Domínios ativos com expirydate de hoje até o limite configurado, inclusive. Inclui renovação automática desativada. O atalho abre todos os domínios ativos. |
| Tickets ativos | List Support Tickets | `supporttickets.php?view=active` | Status com showactive habilitado, somente nos departamentos atribuídos ao administrador. |
| Tickets aguardando resposta | List Support Tickets | `supporttickets.php?view=awaitingreply` | Status com showawaiting habilitado, somente nos departamentos atribuídos. |
| Tickets sinalizados | List Support Tickets + View Flagged Tickets | `supporttickets.php?view=flagged` | Tickets ativos sinalizados para você, nos seus departamentos. |
| Tickets em progresso | List Support Tickets | `supporttickets.php` | Status selecionados em Em progresso, nos seus departamentos. O atalho abre a lista de tickets. |
| Tickets em análise | List Support Tickets | `supporttickets.php` | Status selecionados em Em análise, nos seus departamentos. O atalho abre a lista de tickets. |

Receitas só têm atalho de transações quando o administrador possui List Transactions. Todas as métricas exigem Access Control. Configurações exigem Configure Addon Modules.
