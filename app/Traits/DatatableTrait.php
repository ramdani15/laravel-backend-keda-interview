<?php

namespace App\Traits;

trait DatatableTrait
{
    use ApiFilterTrait;

    /**
     * Filter Datatable
     *
     * @param  object  $obj
     * @param  array  $filter
     * @return object
     */
    public function filterDatatable($query, $filters, $request)
    {
        $limit = $request->limit ?? 10;
        $data = $this->filterFields($query, $filters);
        $data = $this->setOrder($query, [$request->sortBy ?? 'created_at', $request->sort ?? -1]);

        return $data->paginate($limit)->appends($request->input());
    }
}
