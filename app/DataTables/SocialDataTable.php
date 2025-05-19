<?php

namespace App\DataTables;

use App\Models\SiteSetting;
use App\Models\Social;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class SocialDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('icon', function($query){
            return "<i style='font-size:20px' class='".$query->val2."'></i>";
        })
        ->addColumn('title', function($query){
            return $query->val1;
        })
        ->addColumn('link', function($query){
            return $query->val3;
        })
        ->addColumn('status', function($query){
            if($query->val5 == 1) {
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
        ->addColumn('action', function($query){
            $editx = "<a href='".route('admin.social.edit', $query->id)."' class='btn btn-primary'><i class='far fa-edit'></i></a>";
            $deletex = "<a href='".route('admin.social.destroy', $query->id)."' class='btn btn-danger ml-2 delete_item'><i class='far fa-trash-alt'></i></a>";
            return $editx.$deletex;
        })
        ->rawColumns(['title', 'icon', 'status', 'action'])
        ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(SiteSetting $model): QueryBuilder
    {
        return $model->where('key', 'social')->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('social-table')
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
            Column::make('icon'),
            Column::make('title'),
            Column::make('link'),
            Column::make('status'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(200)
                  ->addClass('text-center'),

        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Social_' . date('YmdHis');
    }
}
