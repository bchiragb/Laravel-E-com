<?php

namespace App\DataTables;

use App\Models\Attribute;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class AttributeDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('name', function($query){
            return $query->name;
        })
        ->addColumn('order', function($query){
            return $query->order;
        })
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
        ->addColumn('action', function($query){
            $addx = "<a href='".route('admin.variant.index', ['attr_id' => $query->id])."' class='btn btn-info mr-2'><i class='far fa-edit'></i> Variant</a>";
            $editx = "<a href='".route('admin.attribute.edit', $query->id)."' class='btn btn-primary'><i class='far fa-edit'></i></a>";
            $deletex = "<a href='".route('admin.attribute.destroy', $query->id)."' class='btn btn-danger ml-2 delete_item'><i class='far fa-trash-alt'></i></a>";
            return $addx.$editx.$deletex;
        })
        ->rawColumns(['status', 'action'])
        ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Attribute $model): QueryBuilder
    {
        return $model->where('parent', 0)->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('attribute-table')
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
            Column::make('name'),
            Column::make('order'),
            Column::make('status'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(300)
                  ->addClass('text-center'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Attribute_' . date('YmdHis');
    }
}
