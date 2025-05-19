<?php

namespace App\DataTables;

use App\Models\Coupon;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class CouponDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('status', function($query){
                if($query->status == 1) {
                    $button = '<label class="custom-switch mt-2">
                        <input type="checkbox" checked name="custom-switch-checkbox" data-val="'.$query->id.'" class="custom-switch-input chg_sts">
                        <span class="custom-switch-indicator"></span>
                    </label>';
                } else {
                    $button = '<label class="custom-switch mt-2">
                        <input type="checkbox" name="custom-switch-checkbox" data-val="'.$query->id.'" class="custom-switch-input chg_sts">
                        <span class="custom-switch-indicator"></span>
                    </label>';
                }
                return $button;
            })
            ->addColumn('amount', function($query){
                return $query->amt;
            })
            ->addColumn('type', function($query){
                if($query->type == 0) {
                    return "Amount";
                } else {
                    return "Percentage";
                }
            })
            ->addColumn('per user limit', function($query){
                if($query->limit == "" || $query->limit == 0) {
                    return "Unlimited";
                } else {
                    return $query->limit;
                }
            })
            ->addColumn('total usage', function($query){
                if($query->qty == "" || $query->qty == 0) {
                    return "Unlimited";
                } else {
                    return $query->qty;
                }
            })
            ->addColumn('date', function($query){
                if($query->stdt == $query->eddt) {
                    return "-";
                } else {
                    return $query->stdt.' | '.$query->eddt;
                }
            })
            ->addColumn('action', function($query){
                $editx = "<a href='".route('admin.coupon.edit', $query->id)."' class='btn btn-primary'><i class='far fa-edit'></i></a>";
                $deletex = "<a href='".route('admin.coupon.destroy', $query->id)."' class='btn btn-danger ml-2 delete_item'><i class='far fa-trash-alt'></i></a>";
                return $editx.$deletex;
            })
            ->rawColumns(['type', 'date', 'quantity', 'status', 'action', 'usage'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Coupon $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('coupon-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(0)
                    ->selectStyleSingle()
                    ->buttons([
                        Button::make('excel'),
                        Button::make('csv'),
                        Button::make('pdf'),
                        Button::make('print'),
                        Button::make('reset'),
                        Button::make('reload')
                    ]);
    }

    /**
     * Get the dataTable columns definition.
     */
    public function getColumns(): array
    {
        return [
            Column::make('id'),
            Column::make('code'),
            Column::make('type'),
            Column::make('amount'),
            Column::make('total usage'),
            Column::make('per user limit'),
            Column::make('date'),
            Column::make('status'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(100)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Coupon_' . date('YmdHis');
    }
}
