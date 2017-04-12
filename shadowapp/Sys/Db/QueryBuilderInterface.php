<?php

namespace Shadowapp\Sys\Db;

interface QueryBuilderInterface
{
	/**
     * select  part of query builder.
     *
     * @param  string $selectData
     * @return \Shadowapp\Sys\Db\Query\Builder
     */
	public function select($selectData);

	/**
     * from part of query builder.
     * 
     * @param  string $fromtData
     * @return \Shadowapp\Sys\Db\Query\Builder
     */
	public function from($fromData);

}