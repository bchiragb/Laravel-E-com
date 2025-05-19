<?php

namespace App\DataTables;

use App\Models\ProductVariant;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductVariantDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('price', function($query){
                if($query->sprice != 0) {
                    return $query->sprice." <strike>".$query->rprice.'</strike>';
                } else {
                    return $query->rprice;
                }
            })
            ->addColumn('stock', function($query){
                if($query->stock == 0) {
                    return "<i class='badge badge-danger'>Out of stock</i>";
                } else {
                    return "<i class='badge badge-info'>In Stock</i> ".$query->stock;
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
            ->addColumn('details', function($query){
                //
                $m_attr = \App\Models\Attribute::select('id', 'slug')
                ->where('parent', '!=', '0')
                ->where('status', '1')
                ->get()
                ->mapWithKeys(function ($item) { 
                    return [$item->id => $item->slug];
                })->toArray();
                //
                $col_attr = \App\Models\Attribute::select('id', 'slug')
                ->where('parent', '0')
                ->get()
                ->mapWithKeys(function ($item) { 
                    return [$item->id => $item->slug];
                })
                ->toArray();
                //
                $joinedString = '';
                foreach($col_attr as $colnm){
                    if($query->$colnm != 0) {
                        //echo $colnm.'-'.$query->$colnm;
                        //echo $colnm.'-'.$m_attr[$query->$colnm];
                        $joinedString .= '<b>'.$colnm.'</b>: '.$m_attr[$query->$colnm].' ';
                    } else {
                        //echo $colnm.'='.$query->$colnm;
                    } 
                }
                return $joinedString;
            })
            ->addColumn('action', function($query){
                $editx = "<a href='".route('admin.product-variant.edit', $query->id)."' class='btn btn-primary'><i class='far fa-edit'></i></a>";
                $deletex = "<a href='".route('admin.product-variant.destroy', $query->id)."' class='btn btn-danger ml-2 delete_item'><i class='far fa-trash-alt'></i></a>";
                return $editx.$deletex;
            })
            ->rawColumns(['price', 'stock', 'details', 'status', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(ProductVariant $model): QueryBuilder
    {
        //return $model->newQuery();
        return $model->where('product_id', request()->product)->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('productvariant-table')
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
            Column::make('price'),
            Column::make('stock'),
            Column::make('sku'),
            Column::make('details')->width(300),
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
        return 'ProductVariant_' . date('YmdHis');
    }
}
