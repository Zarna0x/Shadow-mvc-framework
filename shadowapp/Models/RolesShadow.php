<?php

namespace Shadowapp\Models;

use Shadowapp\Sys\Db\Model;

class RolesShadow extends Model
{

    public function __construct()
    {
        parent::__construct();
    }

    // role has many staff members

    public function relateStaff()
    {
        return $this->hasMany('staff', array(
                    'foreign_key' => 'id',
                    'primary_key' => 'role_id'
        ));
    }

}


