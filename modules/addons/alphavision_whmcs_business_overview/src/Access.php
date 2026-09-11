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
use WHMCS\Database\Capsule;
final class Access
{
    public static function admin() { return \WHMCS\User\Admin::getAuthenticatedUser(); }
    public static function can(string $permission): bool
    {
        try { $a = self::admin(); return $a && $a->hasPermission($permission); }
        catch (\Throwable $e) { return false; }
    }
    public static function allowed(): bool
    {
        try {
            $a = self::admin();
            if (!$a || $a->isDisabled) { return false; }
            $roles = Settings::ids((string) Capsule::table('tbladdonmodules')->where('module', AVBO_MODULE)->where('setting', 'access')->value('value'));
            return in_array((int) $a->roleId, $roles, true);
        } catch (\Throwable $e) { return false; }
    }
    public static function departments(): array
    {
        return array_values(array_filter(array_map('intval', self::admin()->getSupportDepartmentIds()), static fn($id) => $id > 0));
    }
    public static function money(array $s): bool
    {
        return $s['money'] && in_array((int) self::admin()->roleId, $s['financial_roles'], true);
    }
    public static function metric(array $def, array $s): bool
    {
        // Callers authorize module access once before evaluating individual indicators.
        return self::can($def['permission']) && ($def['label'] !== 'Tickets sinalizados' || self::can('View Flagged Tickets')) && (!$def['money'] || self::money($s));
    }
}
