# Alphavision WHMCS Business Overview

Administrative widget consolidating business, financial, service and support indicators for WHMCS.

**Version:** 1.0.5 · **Date:** 2026-09-11 · **License:** MIT  
**Compatibility:** WHMCS 9.x, PHP 8.2/8.3, and Blend and Blend New admin themes. Version 1.0.5 was tested on a live WHMCS installation with both themes. The interface is Brazilian Portuguese.

## Installation

1. Upload the ZIP's `modules` directory into your WHMCS root, preserving its structure.
2. Activate **Alphavision WHMCS Business Overview** under Addon Modules.
3. Select and save authorized administrator roles in native **Access Control**.
4. Open the addon and configure indicators, product classification and ticket statuses.
5. Monetary figures are disabled by default. Enable them and explicitly select authorized roles when needed.
6. Enable the dashboard widget in dashboard display options if hidden.

No API keys, cron tasks or core edits are required. There is no client-area endpoint.

## Features

24 selectable indicators in four groups: commercial, financial, services/operations and support. Compact or detailed widget layout, always-detailed addon overview, zero hiding, attention highlighting, configurable domain expiry window and private session cache with manual refresh.

## Definitions and limitations

- Completed monthly orders means orders **created this month and currently Active**, not orders accepted or paid this month.
- Quotes means valid Draft/Delivered quotes. It does not include external quote forms.
- Income uses native GetStats results displayed in the default currency. Reconcile with the installation's financial dashboard before business use.
- Outstanding balances use `max(0, invoice total - invoice credit - net linked payments)`, grouped by client currency. Never sum different currencies. Reconcile credits, partial payments and refunds with native invoice balances.
- Services exclude domains and service addons. Classification priority is explicit product, explicit group, then native type. Hostingaccount maps to hosting, reselleraccount to resellers, and other types to other services.
- Tickets are limited to assigned departments. Flagged tickets must be active and assigned to the current administrator; View Flagged Tickets is also required.
- Expiring domains use expirydate, not billing due date. The interval includes today and the final day.
- Native list shortcuts may be broader than the metric. The interface help and indicator reference document explain these cases.

Native permissions apply to every indicator. Monetary values additionally require an explicit role allowlist. Settings changes require Configure Addon Modules and native CSRF validation. Cached results are isolated by session and current authorization context.

## Documentation and support

Detailed Portuguese documentation is under `docs/`: installation, architecture, indicator definitions, acceptance checklist and local validation limitations. See [SUPPORT.md](SUPPORT.md), [SECURITY.md](SECURITY.md), [CHANGELOG.md](CHANGELOG.md) and [LICENSE](LICENSE).

Developed by [Alphavision®](https://alphavision.com.br/). Repository: [alphavisionbr/whmcs-business-overview](https://github.com/alphavisionbr/whmcs-business-overview).
