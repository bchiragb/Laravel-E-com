<?php

namespace App\DataTables;

use App\Models\Product;
use Illuminate\Database\Eloquent\Builder as QueryBuilder;
use Illuminate\Support\Facades\Request;
use Yajra\DataTables\EloquentDataTable;
use Yajra\DataTables\Html\Builder as HtmlBuilder;
use Yajra\DataTables\Html\Button;
use Yajra\DataTables\Html\Column;
use Yajra\DataTables\Html\Editor\Editor;
use Yajra\DataTables\Html\Editor\Fields;
use Yajra\DataTables\Services\DataTable;

class ProductDataTable extends DataTable
{
    /**
     * Build the DataTable class.
     *
     * @param QueryBuilder $query Results from query() method.
     */
    public function dataTable(QueryBuilder $query): EloquentDataTable
    {
        return (new EloquentDataTable($query))
            ->filter(function ($query) {
                // Apply search for multiple columns if necessary
                if(request()->has('search') && request()->get('search')['value'] != '') {
                    // $search = request()->get('search')['value'];
                    // $query->where('title', 'like', "%{$search}%")
                    //     ->orWhere('category', 'like', "%{$search}%")
                    //     ->orWhere('sku', 'like', "%{$search}%")
                    //     ->orWhere('brand', 'like', "%{$search}%")
                    //     ->orWhere('price', 'like', "%{$search}%");

                    $search = request()->get('search')['value'];
                    $query->where(function ($q) use ($search) {
                        // Apply search to the title, category, sku, brand, and price columns
                        $q->where('title', 'like', "%{$search}%")
                        ->orWhere('sku', 'like', "%{$search}%")
                        ->orWhere('brand', 'like', "%{$search}%")
                        //->orWhere('price', 'like', "%{$search}%")
                        // Apply search to the category name if it's a related table
                        ->orWhereHas('catnm', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        })
                        // Apply search to the brand name if it's a related table
                        ->orWhereHas('brandnm', function($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%");
                        });
                    });
                }
            })
            ->addColumn('image', function($query){
                $imgx1 = "<a href=".asset($query->img1)." target='_blank'><img src=".asset($query->img1)." width='30' height='30'></a>";
                if($query->img2 != ''){
                    $imgx2 = "<a href=".asset($query->img2)." target='_blank'><img src=".asset($query->img2)." width='30' height='30'></a>";
                }
                return $imgx1.$imgx2;
            })
            ->addColumn('title', function($query){
                return "<a href=".Request::root().'/product/'.$query->slug."/><span style='word-wrap: break-word;'>".$query->title."</span></a>";
            })
            ->addColumn('tag', function($query){
                $tagx = explode(',', $query->tag);
                $tags = "";
                if(in_array(1, $tagx)) { $tags .= "New,"; }
                if(in_array(2, $tagx)) { $tags .= "Hot,"; }
                if(in_array(3, $tagx)) { $tags .= "Popular"; }
                return $tags;
            })
            ->addColumn('sale', function($query){
                if($query->stdt != $query->eddt){
                    return "Yes";
                } else {
                    return "No";
                }
                //return $query->stdt.'|'.$query->eddt;
            })
            ->addColumn('type', function($query){
                if($query->ptype == 1) {
                    return "Simple";
                } else {
                    return "Variant";
                }
            })
            ->addColumn('category', function($query){
                return $query->catnm->name;
            })
            ->addColumn('brand', function($query){
                return $query->brandnm->name;
            })
            ->addColumn('price', function($query){
                if($query->ptype == 1) {
                    if($query->sprice != '') {
                        return currencyx().$query->sprice." <strike>".currencyx().$query->rprice.'</strike>';
                    } else {
                        return currencyx().$query->rprice;
                    }
                } else {
                    /* $resultsx1 = \Illuminate\Support\Facades\DB::select("
                    SELECT id, rprice AS min_reg, sprice AS min_sel
                    FROM product_variants WHERE product_id = ".$query->id." AND STATUS = 1 ORDER BY rprice ASC LIMIT 1");
                    $min_reg = $resultsx1[0]->min_reg;
                    $min_sel = $resultsx1[0]->min_sel;
                    if($min_sel != 0) { $lowprice = $min_sel < $min_reg ? $min_sel : $min_reg; } else {  $lowprice = $min_reg; }
                    //
                    $resultsx1 = \Illuminate\Support\Facades\DB::select("
                    SELECT id, rprice AS max_reg, sprice AS max_sel
                    FROM product_variants WHERE product_id = ".$query->id." AND STATUS = 1 ORDER BY rprice DESC LIMIT 1");
                    $max_reg = $resultsx1[0]->max_reg;
                    $max_sel = $resultsx1[0]->max_sel;
                    if($max_sel != 0) { $highprice = $max_sel < $max_reg ? $max_sel : $max_reg; } else {  $highprice = $max_reg; }
                    return $lowprice.' - '.$highprice; */
                    //
                    $mergedValues = \Illuminate\Support\Facades\DB::table('product_variants')
                    ->select('rprice') 
                    ->whereNotNull('rprice')
                    ->where('product_id', $query->id)  
                    ->where('status', 1)
                    ->union(
                        \Illuminate\Support\Facades\DB::table('product_variants')
                        ->select('sprice')  
                        ->whereNotNull('sprice') 
                        ->where('product_id', $query->id)  
                        ->where('status', 1)
                    )
                    ->distinct()
                    ->get();
                    $prices = $mergedValues->pluck('rprice');
                    $filteredPrices = $prices->filter(function($value) { return $value !== 0; });
                    $minPrice = $filteredPrices->min();
                    $maxPrice = $filteredPrices->max();
                    //dd($prices);
                    return currencyx().$minPrice.' - '.currencyx().$maxPrice;
                } 
            })
            ->addColumn('stock', function($query){
                if($query->stock == 0 && $query->ptype == 1) {
                    return "<i class='badge badge-danger'>Out of stock</i>";
                } 
                if($query->stock != 0) {
                    return "<i class='badge badge-warning'>In Stock</i>";
                    
                }
                if($query->stock == 0 && $query->ptype == 2) {
                    return "View Inside";
                } 
            })
            ->addColumn('sku', function($query){
                if($query->ptype == 1) {
                    return $query->sku;
                } else {
                    $skus = \Illuminate\Support\Facades\DB::table('product_variants')
                    ->where('product_id', $query->id)->where('status', 1)->pluck('sku'); 
                    return $skus->implode(', ');
                }
            })
            ->addColumn('status', function($query){
                if($query->sts == 1) {
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
                $editx = "<a href='".route('admin.product.edit', $query->id)."' class='btn btn-primary'><i class='far fa-edit'></i></a>";
                $deletex = "<a href='".route('admin.product.destroy', $query->id)."' class='btn btn-danger ml-2 delete_item'><i class='far fa-trash-alt'></i></a>";
                $moreBtn = '<div class="dropdown dropleft d-inline">
                <button class="btn btn-primary dropdown-toggle ml-1" type="button" id="dropdownMenuButton2" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                <i class="fas fa-cog"></i>
                </button>
                <div class="dropdown-menu" x-placement="bottom-start" style="position: absolute; transform: translate3d(0px, 28px, 0px); top: 0px; left: 0px; will-change: transform;">';
                if($query->ptype == 2) {
                    $moreBtn .=  '<a class="dropdown-item has-icon" href="'.route('admin.product-variant.index', ['product' => $query->id]).'"><i class="far fa-file"></i> Add Variants</a>';
                }
                $moreBtn .=  '<a class="dropdown-item has-icon" href="'.route('admin.product-imgs.index', ['product' => $query->id]).'"><i class="far fa-heart"></i> Add More Images</a>
                <a class="dropdown-item has-icon" href="'.route('admin.review.index', ['product' => $query->id]).'"><i class="far fa-heart"></i>Add Product Review</a>
                </div>
                </div>';
                return $moreBtn.$editx.$deletex;
            })
            ->rawColumns(['title', 'image', 'stock', 'price', 'status', 'action'])
            ->setRowId('id');
    }

    /**
     * Get the query source of dataTable.
     */
    public function query(Product $model): QueryBuilder
    {
        return $model->newQuery();
    }

    /**
     * Optional method if you want to use the html builder.
     */
    public function html(): HtmlBuilder
    {
        return $this->builder()
                    ->setTableId('product-table')
                    ->columns($this->getColumns())
                    ->minifiedAjax()
                    //->dom('Bfrtip')
                    ->pageLength(20)
                    ->lengthMenu([ [10, 20, 25, 50, -1], [10, 20, 25, 50, "All"] ])
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
            Column::make('title'),
            Column::make('image')->width(65),
            Column::make('category'),
            Column::make('brand'),
            //Column::make('tag'),
            Column::make('sale'),
            //Column::make('type'),
            Column::make('price'),
            //Column::make('stock'),
            Column::make('sku')->width(100),
            Column::make('status'),
            Column::computed('action')
                  ->exportable(false)
                  ->printable(false)
                  ->width(100)
                  ->addClass('text-right'),
        ];
    }

    /**
     * Get the filename for export.
     */
    protected function filename(): string
    {
        return 'Product_' . date('YmdHis');
    }
}
