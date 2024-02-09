<?php

namespace App\Services\Auth\Traits;

use Illuminate\Support\Arr;

/**
 * Authorizable
 *
 * Used in Controller class.
 */
trait Authorizable
{
    private $abilities = [
        'index' => 'view',
        'edit' => 'edit',
        'show' => 'view',
        'update' => 'edit',
        'create' => 'add',
        'store' => 'add',
        'destroy' => 'delete',
    ];

    /**
     * Override of callAction to perform the authorization before
     *
     * @return mixed
     */
    public function callAction($method, $parameters)
    {
        if ($ability = $this->getAbility($method)) {
            $this->authorize($ability);
        }

        return parent::callAction($method, $parameters);
    }

    public function getAbility($method)
    {
        $routeName = request()->route()->getName();
        $action = Arr::get($this->getAbilities(), $method);

        return $routeName;
    }

    private function getAbilities()
    {
        return $this->abilities;
    }

    public function setAbilities($abilities)
    {
        $this->abilities = $abilities;
    }
}
