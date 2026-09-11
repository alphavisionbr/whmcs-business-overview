# Homologação da versão 1.0.1

Execute em cópia da instalação com dados conhecidos. Registre versão exata do WHMCS/PHP/tema e resultados antes de publicar como homologado.

## Instalação e interface

- Ativar, salvar Access Control, abrir addon e mostrar widget no dashboard.
- Conferir Blend em tela larga e estreita, textos, contraste e atalhos.
- Salvar configurações e reabrir: grupos, indicadores, zeros, layout e janela 7/15/30.
- Selecionar o mesmo produto/grupo em duas categorias: salvamento deve ser rejeitado.
- Desativar e confirmar ausência do widget; reativar e confirmar preferências preservadas.

## Comercial

- Pedido criado hoje, criado ontem, criado em outro mês e pedido futuro.
- Pedido antigo pendente deve entrar no total de pendentes.
- Pedido criado neste mês Active entra em completos; pedido antigo aceito neste mês não entra.
- Orçamentos Draft/Delivered válidos, vencidos, validade hoje e Accepted/Lost/Dead.
- Novos clientes no limite da virada do mês e clientes inativos.

## Financeiro

- Habilitar monetários para um perfil autorizado.
- Conciliar receita hoje/mês com o WHMCS e a moeda padrão.
- Fatura 100 sem pagamentos; com crédito 20; com pagamento 30; com ambos; com saída/reembolso; e com recebimentos excedentes.
- Conferir saldos individuais nativos e depois o agregado. Nenhuma divergência deve ser aceita apenas porque o número parece plausível.
- Duas moedas diferentes devem aparecer separadas.
- Fatura com vencimento hoje não é atrasada; ontem é atrasada.
- Faturas Paid/Cancelled/Refunded não devem entrar nos totais Unpaid.

## Operação e suporte

- A soma Hospedagem + Revenda + Outros deve igualar Serviços ativos.
- Conferir precedência de produto sobre grupo e grupo sobre tipo nativo.
- Serviços suspensos/pendentes e cancelamento duplicado para o mesmo serviço.
- Domínio expirando hoje, no último dia, depois do limite e já expirado.
- Dois administradores com departamentos diferentes e tickets sinalizados para pessoas diferentes.
- Status personalizado com showactive/showawaiting e seleção de progresso/análise.
- Administrador sem departamentos deve ver zero tickets.

## Permissões e cache

- Perfil sem Access Control não abre addon nem recebe o widget.
- Retirar List Invoices, View Income Totals, List Services, List Domains, Manage Quotes e View Flagged Tickets: respectivas métricas devem desaparecer conforme o caso.
- Habilitação monetária sem perfis selecionados não autoriza ninguém.
- Dois administradores: não compartilhar resultados.
- Remover permissão/departamento enquanto há cache: resultado deve obedecer à nova autorização.
- Alterar configuração em outra sessão: resultado deve refletir nova configuração no próximo acesso.
- POST sem token válido não deve salvar nem atualizar.
- Usuário sem Configure Addon Modules não deve conseguir salvar via POST direto.
- Forçar erro em uma consulta de homologação: indicador deve mostrar Indisponível, nunca zero, sem expor SQL ou dados de clientes.
- Conferir tempo de coleta com base maior e cache habilitado.
