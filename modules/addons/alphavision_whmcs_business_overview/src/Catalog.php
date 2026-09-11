<?php
/**
 * Alphavision WHMCS Business Overview
 * @package AlphavisionWHMCSBusinessOverview
 * @author Alphavision®
 * @copyright Copyright (c) 2026 Alphavision®
 * @license MIT
 * @version 1.0.5
 * @link https://alphavision.com.br/
 */
declare(strict_types=1);
namespace Alphavision\BusinessOverview;
final class Catalog
{
    public static function groups(): array { return ['commercial'=>'Comercial','financial'=>'Financeiro','operations'=>'Serviços e operação','support'=>'Suporte']; }
    public static function all(): array
    {
        return [
            'clients_active'=>['group'=>'commercial','label'=>'Clientes ativos','permission'=>'List Clients','url'=>'clients.php?status=Active','help'=>'Clientes com status Active.','money'=>false,'attention'=>false],
            'orders_today'=>['group'=>'commercial','label'=>'Pedidos hoje','permission'=>'View Orders','url'=>'orders.php','help'=>'Pedidos criados hoje, em qualquer status.','money'=>false,'attention'=>false],
            'orders_pending'=>['group'=>'commercial','label'=>'Pedidos pendentes','permission'=>'View Orders','url'=>'orders.php?status=Pending','help'=>'Todos os pedidos atualmente pendentes, independentemente da data.','money'=>false,'attention'=>true],
            'orders_complete'=>['group'=>'commercial','label'=>'Pedidos completos no mês','permission'=>'View Orders','url'=>'orders.php?status=Active','help'=>'Pedidos criados neste mês e atualmente Active. Não representa a data de aceitação ou pagamento. O atalho abre todos os pedidos ativos.','money'=>false,'attention'=>false],
            'quotes'=>['group'=>'commercial','label'=>'Solicitações de orçamento','permission'=>'Manage Quotes','url'=>'quotes.php?validity=Valid','help'=>'Orçamentos Draft ou Delivered ainda válidos, incluindo o dia de validade. Não inclui aceitos, perdidos ou encerrados.','money'=>false,'attention'=>true],
            'clients_new'=>['group'=>'commercial','label'=>'Novos clientes no mês','permission'=>'List Clients','url'=>'clients.php','help'=>'Cadastros de clientes criados neste mês. O atalho abre a lista de clientes.','money'=>false,'attention'=>false],
            'income_today'=>['group'=>'financial','label'=>'Receita hoje','permission'=>'View Income Totals','url'=>'transactions.php','help'=>'Receita retornada pelo GetStats nativo na moeda padrão. Consulte a definição do WHMCS; não é lucro.','money'=>true,'attention'=>false],
            'income_month'=>['group'=>'financial','label'=>'Receita no mês','permission'=>'View Income Totals','url'=>'transactions.php','help'=>'Receita do mês retornada pelo GetStats nativo na moeda padrão. O atalho abre todas as transações.','money'=>true,'attention'=>false],
            'invoices_open'=>['group'=>'financial','label'=>'Faturas em aberto','permission'=>'List Invoices','url'=>'invoices.php?status=Unpaid','help'=>'Faturas Unpaid, incluindo as atrasadas.','money'=>false,'attention'=>false],
            'balance_open'=>['group'=>'financial','label'=>'Valor total em aberto','permission'=>'List Invoices','url'=>'invoices.php?status=Unpaid','help'=>'Saldo por moeda: total menos crédito e recebimentos líquidos de saídas; mínimo zero por fatura.','money'=>true,'attention'=>false],
            'invoices_overdue'=>['group'=>'financial','label'=>'Faturas atrasadas','permission'=>'List Invoices','url'=>'invoices.php?status=Overdue','help'=>'Faturas Unpaid com vencimento anterior a hoje. As que vencem hoje não estão atrasadas.','money'=>false,'attention'=>true],
            'balance_overdue'=>['group'=>'financial','label'=>'Valor total atrasado','permission'=>'List Invoices','url'=>'invoices.php?status=Overdue','help'=>'Saldo das faturas atrasadas separado por moeda.','money'=>true,'attention'=>true],
            'hosting_reseller'=>['group'=>'operations','label'=>'Hospedagem/Revendas ativas','permission'=>'List Services','url'=>'clientshostinglist.php?status=Active','help'=>'Classificação: produto explícito, grupo explícito, tipo nativo. O atalho abre todos os serviços ativos.','money'=>false,'attention'=>false],
            'other'=>['group'=>'operations','label'=>'Outros serviços ativos','permission'=>'List Services','url'=>'clientshostinglist.php?status=Active','help'=>'Serviços ativos não classificados como Hospedagem ou Revenda. O atalho abre todos os serviços ativos.','money'=>false,'attention'=>false],
            'domains_active'=>['group'=>'operations','label'=>'Domínios ativos','permission'=>'List Domains','url'=>'clientsdomainlist.php?status=Active','help'=>'Domínios com status Active.','money'=>false,'attention'=>false],
            'services_suspended'=>['group'=>'operations','label'=>'Serviços suspensos','permission'=>'List Services','url'=>'clientshostinglist.php?status=Suspended','help'=>'Serviços com status Suspended.','money'=>false,'attention'=>true],
            'services_pending'=>['group'=>'operations','label'=>'Serviços pendentes','permission'=>'List Services','url'=>'clientshostinglist.php?status=Pending','help'=>'Serviços com status Pending.','money'=>false,'attention'=>true],
            'cancellations'=>['group'=>'operations','label'=>'Cancelamentos pendentes','permission'=>'View Cancellation Requests','url'=>'cancelrequests.php','help'=>'Serviços distintos com solicitação de cancelamento, ainda não Cancelled ou Terminated.','money'=>false,'attention'=>true],
            'domains_expiring'=>['group'=>'operations','label'=>'Domínios próximos do vencimento','permission'=>'List Domains','url'=>'clientsdomainlist.php?status=Active','help'=>'Domínios ativos com expirydate de hoje até o limite configurado, inclusive. Inclui renovação automática desativada. O atalho abre todos os domínios ativos.','money'=>false,'attention'=>true],
            'tickets_active'=>['group'=>'support','label'=>'Tickets ativos','permission'=>'List Support Tickets','url'=>'supporttickets.php?view=active','help'=>'Status com showactive habilitado, somente nos departamentos atribuídos ao administrador.','money'=>false,'attention'=>false],
            'tickets_waiting'=>['group'=>'support','label'=>'Tickets aguardando resposta','permission'=>'List Support Tickets','url'=>'supporttickets.php?view=awaitingreply','help'=>'Status com showawaiting habilitado, somente nos departamentos atribuídos.','money'=>false,'attention'=>true],
            'tickets_flagged'=>['group'=>'support','label'=>'Tickets sinalizados','permission'=>'List Support Tickets','url'=>'supporttickets.php?view=flagged','help'=>'Tickets ativos sinalizados para você, nos seus departamentos.','money'=>false,'attention'=>true],
            'tickets_progress'=>['group'=>'support','label'=>'Tickets em progresso','permission'=>'List Support Tickets','url'=>'supporttickets.php','help'=>'Status selecionados em Em progresso, nos seus departamentos. O atalho abre a lista de tickets.','money'=>false,'attention'=>false],
            'tickets_review'=>['group'=>'support','label'=>'Tickets em análise','permission'=>'List Support Tickets','url'=>'supporttickets.php','help'=>'Status selecionados em Em análise, nos seus departamentos. O atalho abre a lista de tickets.','money'=>false,'attention'=>false],
        ];
    }
}
