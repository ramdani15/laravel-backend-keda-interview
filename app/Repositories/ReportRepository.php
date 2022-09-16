<?php

namespace App\Repositories;

use Facades\App\Models\Report;
use App\Resources\Api\V1\ReportResource;
use App\Traits\DatatableTrait;
use Facades\App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class ReportRepository extends BaseRepository
{
    use DatatableTrait;

    /**
     * Get Available Report Types
     *
     * @return array
     */
    private function availableTypes()
    {
        return ['customer', 'bug', 'feedback'];
    }

    /**
     * Get Datatables Reports
     *
     * @return Json|array
     */
    public function datatable(Request $request)
    {
        try {
            $query = Report::with('reportable');
            $filters = [
                [
                    'field' => 'id',
                    'value' => $request->id,
                ],
                [
                    'field' => 'type',
                    'value' => $request->type,
                ],
            ];
            $request->sortBy = $request->sortBy ?? 'id';
            $request->sort = $request->sort ?? -1;
            $data = $this->filterDatatable($query, $filters, $request);

            return ReportResource::collection($data);
        } catch (\Throwable $th) {
            //throw $th;
            Log::error($th);

            return $this->setResponse(false, __('Failed get reports'));
        }
    }

    /**
     * Create Report
     *
     * @return Json|array
     */
    public function create($data)
    {
        \DB::beginTransaction();
        try {
            if (!in_array($data['type'] ?? '', $this->availableTypes())) {
                return $this->setResponse(false, __('Report type not available'));
            }

            $report = Report::create($data);

            if ($report->type == 'customer') {
                $customer = User::where([
                    'id' => $data['customer_id'] ?? '',
                    'user_type_id' => 1
                ])->first();
                if (!$customer) {
                    \DB::rollback();
                    return $this->setResponse(false, __('Customer not found'));
                } elseif ($customer->id == auth()->id()) {
                    \DB::rollback();
                    return $this->setResponse(false, __('Customer invalid'));
                }
                $customer->report()->save($report);
            }

            $data = new ReportResource($report);

            \DB::commit();

            return $this->setResponse(true, __('Create report successfully'), $data);
        } catch (\Throwable $th) {
            //throw $th;
            \DB::rollback();
            Log::error($th);

            return $this->setResponse(false, __('Create report failed'));
        }
    }
}
