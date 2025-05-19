<?php

namespace App\DataTables;

use App\Models\ProductCategory;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductCategoryDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
        ->addColumn('image', function($query){
            if($query->img == "") {
                return "-";
            } else {
                return "<img src=".asset($query->img)." width='100' height='100'>";
            }
            
        })
        ->addColumn('title', function($query){
            return $query->name;
        })
        ->addColumn('parent', function($query){
            if($query->parent == 0) {
                return 'No';
            } else {
                //dd($query->parentcat);
                //return $query->parent;
                return $query->parentcat->name;
            }
        })
        ->addColumn('Homepage', function($query){
            if($query->featured == 0) {
                return "<i class='badge badge-info'>No</i>";
            } else {
                return "<i class='badge badge-warning'>Yes</i>";
            }
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
            $editx = "<a href='".route('admin.product-category.edit', $query->id)."' class='btn btn-primary'><i class='far fa-edit'></i></a>";
            $deletex = "<a href='".route('admin.product-category.destroy', $query->id)."' class='btn btn-danger ml-2 delete_item'><i class='far fa-trash-alt'></i></a>";
            return $editx.$deletex;
        })
        ->rawColumns(['image', 'Homepage', 'status', 'action'])
        ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ProductCategory $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    //->setTableId('productcategory-table')
                    ->setTableId('productcategory-table')
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
            Column::make('image'),
            Column::make('name'),
            Column::make('parent'),
            Column::make('Homepage'),
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
        return 'ProductCategory_' . date('YmdHis');
    }
}
