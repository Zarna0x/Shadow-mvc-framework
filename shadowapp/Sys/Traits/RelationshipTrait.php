<?php

namespace Shadowapp\Sys\Traits;

trait RelationshipTrait
{
	protected $allowedRelationshipMethods = [
       'hasone',
       'belongsto',
       'hasmany',
	];


	public function makeBelongsTo($args )
	{

	    $fromTable = trim(shcol('0',$args));
        $keyMapping = shcol('1',$args);
		$sql = "SELECT * FROM ".$fromTable." WHERE ".shcol('primary_key',$keyMapping)." = ?";
        return [
          'rel_tbl_name' => $fromTable,
          'primary_key' => shcol('primary_key',$keyMapping),
          'foreign_key' => shcol('foreign_key',$keyMapping),
          'sql' => $sql,
        ];
    }
}