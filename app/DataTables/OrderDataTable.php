<?php

namespace App\DataTables;

use App\Models\Order;
use App\Models\User;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class OrderDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->addColumn('order_no', function($query){
                return $query->order_id;  
            })
            ->addColumn('order_date', function($query){
                return $query->order_date;  
            })
            ->addColumn('order_user', function($query){
                $user = User::where('id', $query->customer_id)->get();
                return $user[0]->email." (".$user[0]->role.")";
            })
            ->addColumn('order_amt', function($query){
                return currencyx().$query->total_amount;  
            })
            ->addColumn('order_status', function($query){
                return order_sts($query->order_status);  
            })
            ->addColumn('payment_mode', function($query){
                return $query->payment_mode;  
            })
            ->addColumn('ship_details', function($query){
                $shipdata = DB::table('ship')->where('order_id', $query->order_id)->get();
                $shipcount = $shipdata->count();  // Count the number of records in the collection
                $shipreco = $shipdata->first();
                if ($shipcount > 0) {
                    return $shipreco->sno.' <a href="'.$shipreco->surl.'">view</a>';
                } else {
                    return ; 
                }
            })
            ->addColumn('action', function($query){
                $editx = "<a href='".route('admin.order.show', $query->order_id)."' class='btn btn-primary'><i class='far fa-edit'></i></a>";
                $deletex = "<a href='".route('admin.order.destroy', $query->order_id)."' class='btn btn-danger ml-2 delete_item'><i class='far fa-trash-alt'></i></a>";
                return $editx.$deletex;
            })
            ->rawColumns(['action', 'ship_details'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Order $model): QueryBuilder
    {
        return $model->newQuery();
        //return $model->newQuery()
        //->join('ship', 'ship.order_id', '=', 'orders.order_id')
        //->select('orders.*', 'ship.*');
        //->select('order_views.*', 'users.name as user_name', 'users.email as user_email');
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('order-table')
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
            Column::make('order_no'),
            Column::make('order_date'),
            Column::make('order_user'),
            Column::make('order_amt'),
            Column::make('order_status'),
            Column::make('payment_mode'),
            Column::make('ship_details'),
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
        return 'Order_' . date('YmdHis');
    }
}
