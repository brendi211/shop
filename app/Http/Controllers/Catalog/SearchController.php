<?php

namespace App\Http\Controllers\Catalog;

use App\Models\SearchLog;
use App\Repositories\ProductsRepository;
use Illuminate\Http\Request;

class SearchController extends CatalogController
{
    private $productsRepository;

    public function __construct(ProductsRepository $productsRepository)
    {
        $this->productsRepository = $productsRepository;
    }

    public function index()
    {
        abort_if(!request('query'), 404);

        $products = $this->productsRepository->searchProducts(request('query'));

        $data = [
//            'meta_keywords'    => __('search.meta_keywords'),
//            'meta_description' => __('search.meta_description'),
            'products'         => $products,
            'searchString'     => request('query'),
        ];

        $this->saveLog();

        return view('catalog.search.index', $data);
    }

    public function searchLive(Request $request)
    {
        $products = $this->productsRepository->searchProductsLive($request->input('query'))->get();
        $countProducts = $products->count();

        $htmlResultSearch = '';
        $i = 0;
        foreach ($products as $product) {
            $htmlResultSearch .= '<a href="' . $product->url .'">';
            $htmlResultSearch .= '<img src="' . $product->small . '" alt="' . $product->name .'" width="50">';
            $htmlResultSearch .= '<span style="color: black;">' . $product->name .'</span><br>';
            if ($product->is_discounted) {
                $htmlResultSearch .= '<span style="color: black; text-decoration: line-through;">' . $product->new_price . ' грн </span>';
                $htmlResultSearch .= '<span style="color: black; font-weight: 600">' . $product->old_price . ' грн</span>';
            } else {
                $htmlResultSearch .= '<span style="color: black;">' . $product->new_price .' грн</span>';
            }
            $htmlResultSearch .= '</a>';
            $i++;
            
            if ($i == 4) {
                break;
            }
        }
        
        if ($countProducts > 4) {
            $htmlResultSearch .= '<a href="' . route('search') . '?query=' . str_replace(' ', '+', $request->input('query')) .'" class="all-results">Показать всё</a>';
        }

        if (empty($htmlResultSearch)) {
            $htmlResultSearch = 'Нету результатов по данному запросу';
        }

        return $htmlResultSearch;
    }

    private function saveLog()
    {
        SearchLog::create([
            'query'      => request('query'),
            'user_agent' => request()->userAgent(),
            'is_show'    => false
        ]);
    }
}
