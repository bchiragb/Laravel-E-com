<?php

namespace App\DataTables;

use App\Models\seo;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class seoDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTablex(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable(Seo::query()))
        // return (new EloquentDataTable($query))
             ->addColumn('Title', function($query){
                 return $query->pid;
             })
             ->addColumn('Type', function($query){
                if($query->ptype == 1) {
                    return "Product";
                } elseif($query->ptype == 2) {
                    return "Page";
                } else {
                    return "custom";
                }
             })
             ->addColumn('Seo_Title', function($query){
                 return $query->title;
             })
             ->addColumn('Seo_Desc', function($query){
                 return $query->desc;
             })
             ->addColumn('action', function($query){
                 $editx = "<a href='".route('admin.product.edit', $query->id)."' class='btn btn-primary'><i class='far fa-edit'></i></a>";
                 $deletex = "<a href='".route('admin.product.destroy', $query->id)."' class='btn btn-danger ml-2 delete_item'><i class='far fa-trash-alt'></i></a>";
                 return $editx.$deletex;
             })
             ->setRowId('id');

    }

    public function dataTable(QueryBuilder $query): EloquentDataTable
{
    // Fetch your data from the database using query builder
    $queryBuilder = Seo::query();

    // Define the custom row
    $customRow = (object)[
        'id' => null,  // Set to null or any value to identify it's a custom row
        'pid' => 'Custom Row',  
        'ptype' => 'Custom Type',
        'title' => 'Custom Title',
        'desc' => 'Custom Description',
        'action' => 'Custom Action',
    ];

    // Combine the custom row with the database query results
    $data = $queryBuilder->get()->push($customRow);  // Add the custom row to the data collection

    // Pass the combined data to the EloquentDataTable (force it to be a collection, not a builder)
    return (new EloquentDataTable($data))
        ->addColumn('Title', function($query) {
            return $query->pid;  
        })
        ->addColumn('Type', function($query) {
            return $query->ptype == 1 ? 'Product' : 'Page';  
        })
        ->addColumn('Seo_Title', function($query) {
            return $query->title;  
        })
        ->addColumn('Seo_Desc', function($query) {
            return $query->desc;  
        })
        ->addColumn('action', function($query) {
            return $query->id ? 
                "<a href='".route('admin.product.edit', $query->id)."' class='btn btn-primary'><i class='far fa-edit'></i></a>" : 
                "<span>No Action</span>";  // Handle case when it's a custom row
        })
        ->setRowId('id');
}


    /**
     * Get the query source of dataTable.
     */
    public function query(seo $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('seo-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->orderBy(1)
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
            Column::make('Title'),
            Column::make('Type'),
            Column::make('Seo_Title'),
            Column::make('Seo_Desc'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(60)
                  ->addClass('text-center'),
            
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'seo_' . date('YmdHis');
    }
}
