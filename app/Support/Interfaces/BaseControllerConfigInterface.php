<?php
/**
 * Created by PhpStorm.
 * User: blessing
 * Date: 25/08/2023
 * Time: 12:08 pm
 */

namespace App\Support\Interfaces;

use Illuminate\Support\Collection;

interface BaseControllerConfigInterface
{

    /**
     * @param array $filterOptions
     * @return Collection|null|array
     */
    public function findByFilters(array $filterOptions): Collection|null|array;

}
