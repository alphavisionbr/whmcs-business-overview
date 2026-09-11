# Instalação e remoção

## Estrutura do ZIP

A pasta `modules/` está diretamente na raiz do pacote. Não existe uma pasta adicional envolvendo a instalação.

```text
modules/addons/alphavision_whmcs_business_overview/
```

README, licença e documentos ficam fora da pasta do addon. Envie apenas `modules` ao WHMCS; mantenha a documentação com o projeto.

## Ativação

1. Faça a instalação inicial em homologação.
2. Copie `modules/` para a raiz do WHMCS.
3. Ative o addon em Configurações > Módulos adicionais.
4. Configure o Access Control nativo e salve os perfis.
5. Abra Addons > Alphavision WHMCS Business Overview.
6. Use Configurações para ajustar apresentação, indicadores, acesso monetário, produtos, grupos e status.
7. No dashboard, habilite o widget em suas opções de exibição.
8. Abra Diagnóstico, clique em Atualizar indicadores e siga HOMOLOGACAO.md.

As configurações detalhadas ficam dentro do addon. A tela nativa mantém ativação e Access Control. Todas as seções de configuração têm título, descrição e ajuda abaixo dos campos.

## Atualizações

Substitua os arquivos do módulo por completo preservando a estrutura. Não desative o addon apenas para atualizar. O cache inclui a versão do pacote. Esta primeira versão não migra dados de qualquer outra instalação ou ferramenta.

## Desativação

Desative no gerenciador nativo. O widget deixa de ser registrado e a autorização deixa de passar pelo Access Control removido pelo WHMCS. A tabela de preferências é preservada para eventual reativação.

## Remoção completa

1. Desative o addon.
2. Exclua somente `modules/addons/alphavision_whmcs_business_overview/`.
3. Se não quiser preservar preferências, após backup, remova a tabela `mod_alphavision_business_overview` pelo seu gerenciador de banco.

Nenhuma tabela nativa de clientes, pedidos, faturas ou tickets é modificada. O cache da sessão expira sem exigir limpeza manual.

## Diagnóstico

- Widget ausente: confira ativação, Access Control, opção Widget administrativo e opções do dashboard.
- Valores monetários ausentes: confira habilitação, lista de perfis e permissões View Income Totals/List Invoices.
- Todos os tickets zerados: confira departamentos atribuídos ao administrador.
- Indicador Indisponível: confira o diagnóstico e a compatibilidade da versão do WHMCS. Esse estado não significa zero.
- Salvar não altera configurações: confirme Configure Addon Modules e que o formulário não excedeu max_input_vars do PHP. Formulários truncados não são salvos.

## Apresentação e acesso na versão 1.0.1

A escolha de layout afeta somente o widget. A visão geral do addon sempre exibe as descrições. O menu administrativo usa Business Overview; o nome oficial permanece Alphavision WHMCS Business Overview.

O Access Control é configurado somente na tela nativa do WHMCS. Os perfis com acesso aos valores monetários restringem dinheiro em ambas as superfícies e não substituem o Access Control nem as permissões nativas.

## Configuração a partir da versão 1.0.3

O layout do widget, zeros, destaques, janela dos domínios e cache do addon ficam na tela nativa Configure. O cache não se aplica ao widget. Seleção de indicadores, perfis financeiros, classificação e suporte permanecem na guia Configurações do addon. Os valores anteriores são usados até o primeiro salvamento na configuração nativa.
