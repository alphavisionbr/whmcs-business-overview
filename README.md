# Alphavision WHMCS Business Overview

Widget administrativo para visão consolidada dos principais indicadores comerciais, financeiros, operacionais e de suporte do WHMCS.

**Versão:** 1.0.5 · **Data:** 11/09/2026 · **Licença:** MIT  
**Compatibilidade alvo:** WHMCS 9.x, PHP 8.2/8.3 e tema administrativo Blend. Homologação em ambiente real pendente.

## Instalação rápida

1. Envie a pasta `modules` do ZIP para a raiz da instalação do WHMCS, preservando a estrutura.
2. Abra **Configurações > Módulos adicionais** (Addon Modules), localize **Alphavision WHMCS Business Overview** e ative.
3. Clique em Configurar, selecione os perfis no **Access Control** e salve.
4. Abra o módulo pelo menu Addons. Em **Configurações**, selecione os indicadores e ajuste a classificação de serviços.
5. Para mostrar receitas e saldos, habilite valores monetários e selecione os perfis autorizados. Os valores começam ocultos.
6. Volte ao dashboard e habilite o widget nas opções de exibição, se ele estiver oculto.

Não é necessário criar cron, cadastrar API Key ou modificar arquivos nativos. O módulo não cria uma página na Central do Cliente.

## Indicadores

- **Comercial:** pedidos hoje, pendentes, completos no mês, pedidos de orçamento, novos clientes no mês e clientes ativos.
- **Financeiro:** receita hoje e no mês; quantidade e saldo de faturas abertas e atrasadas.
- **Serviços e operação:** serviços ativos, sua composição em hospedagens/revendas/outros, suspensos, pendentes, cancelamentos pendentes, domínios ativos e próximos da expiração.
- **Suporte:** tickets ativos, aguardando resposta, sinalizados para o administrador, em progresso e em análise.

São 24 indicadores selecionáveis. O widget ocupa duas colunas do dashboard. A disposição interna se adapta à largura da tela.

## Regras que afetam a interpretação

**Pedidos completos no mês:** pedidos criados no mês corrente com status atual Active. Não é uma contagem por data de aceitação, pagamento ou conclusão. Pedidos criados em meses anteriores e aceitos neste mês não entram. Um pedido ativo não garante pagamento.

**Solicitações de orçamento:** registros do sistema de orçamentos em Draft ou Delivered cuja validade ainda não venceu. Não captura formulários externos ou tickets comerciais.

**Receita:** valores retornados pelo GetStats do WHMCS, exibidos na moeda padrão. Não representam lucro ou MRR. A conciliação com o painel financeiro da instalação é parte obrigatória da homologação.

**Saldos:** por fatura Unpaid, calcula `máximo(0, total - crédito - recebimentos líquidos de saídas vinculadas)`. Agrupa por moeda do cliente, sem somar moedas diferentes. As quantidades incluem todas as faturas Unpaid, mesmo com saldo zero. Abertas incluem atrasadas. Créditos e pagamentos parciais precisam ser conciliados com faturas reais antes de usar os totais para decisões.

**Serviços:** não incluem domínios ou addons de serviços. Cada serviço ativo aparece em exatamente uma categoria. Precedência: produto selecionado, grupo selecionado, tipo nativo. Produtos de tipo hostingaccount e reselleraccount são classificados automaticamente. Os demais vão para Outros.

**Suporte:** departamentos atribuídos são obrigatórios. Permissão de acesso direto a outros tickets não amplia os agregados. Sinalizados exige também View Flagged Tickets e conta tickets ativos marcados para o próprio administrador.

**Domínios a vencer:** considera expirydate entre hoje e hoje + 7/15/30 dias, inclusive. Não usa nextduedate nem exclui domínios com renovação automática desativada.

**Atalhos:** apontam para listagens nativas. Nem todos reproduzem exatamente o recorte do indicador. As definições ao passar o mouse e a documentação indicam quando o destino é mais amplo.

## Configurações e permissões

As preferências de apresentação são globais. O acesso depende do Access Control nativo e das permissões específicas para cada indicador. Configurar o addon exige Configure Addon Modules. Valores monetários exigem habilitação global e perfil explicitamente autorizado, além da permissão nativa.

O cache dura 60 segundos por padrão, configurável entre 0 e 300 segundos. É privado da sessão e revalidado contra administrador, perfil, departamentos, indicadores permitidos, configuração, data e fuso. O botão Atualizar indicadores força uma nova coleta. Não existe atualização em segundo plano.

## Documentação

- [Instalação e remoção](docs/INSTALACAO.md)
- [Arquitetura e decisões da versão inicial](docs/ARQUITETURA.md)
- [Definições, permissões e destinos](docs/INDICADORES.md)
- [Roteiro de homologação](docs/HOMOLOGACAO.md)
- [Validação local e limitações](docs/VALIDACAO.md)
- [Histórico de versões](CHANGELOG.md)
- [Suporte](SUPPORT.md)
- [Segurança](SECURITY.md)

## Projeto gratuito

Desenvolvido pela [Alphavision®](https://alphavision.com.br/). Contribua relatando problemas, propondo melhorias ou enviando contribuições de código.

Repositório: [alphavisionbr/whmcs-business-overview](https://github.com/alphavisionbr/whmcs-business-overview).
