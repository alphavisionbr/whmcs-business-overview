# Política de segurança

Relate vulnerabilidades em particular para [contato@alphavision.com.br](mailto:contato@alphavision.com.br), com assunto Business Overview: segurança. Não divulgue credenciais, dados de clientes ou exploração em issue pública.

Envie a versão, impacto e reprodução mínima em ambiente controlado. A linha 1.0.x é a linha inicial do projeto; não há SLA de correção.

O módulo não requer API Key, não expõe endpoint cliente e não altera registros comerciais. Configurações exigem autorização administrativa e token CSRF nativo. Valores monetários usam lista explícita de perfis, além de permissões nativas. Cache é restrito à sessão e ao contexto de autorização.
