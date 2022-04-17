<?php

namespace Shahnewaz\Permissible;

use Illuminate\Database\Eloquent\Model;

class Permission extends Model
{

    public $timestamps = false;

    protected $fillable = ['type', 'name'];

    /**
     * Creates a Permission passed in the form `type.name`
     *
     * @param $permission
     */
    public static function createPermission($permission)
    {
        $params = self::getPermissionParts($permission);
        return static::updateOrCreate($params);
    }

    /**
     * @param $permission
     * @return array
     */
    public static function getPermissionParts($permission)
    {
        $parts = explode('.', $permission);

        $params = [
            'type' => isset($parts[0]) ? $parts[0] : null,
            'name' => isset($parts[1]) ? $parts[1] : null
        ];

        return $params;
    }

    /**
     * Finds a Permission passed in the form `type.name`
     *
     * @param $permission
     */
    public static function getPermission($permission)
    {
        $params = self::getPermissionParts($permission);
        return static::where($params)->first();
    }

    public function roles()
    {
        return $this->belongsToMany(Role::class, 'role_permission');
    }
}
