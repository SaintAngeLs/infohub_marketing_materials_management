<?php

namespace App\Http\Controllers\Search;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Branch;
use App\Models\Auto;
use App\Models\MenuItems\MenuItem;
use App\Models\File;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Log;

class SearchController extends Controller
{
    public function search(Request $request)
    {
        $query = $request->input('query');
        $results = new Collection();
        Log::info('Search Query: ' . $query);


        if (!empty($query)) {
            // Search in Branches
            $branches = Branch::where('name', 'LIKE', "%{$query}%")
                            ->orWhere('address', 'LIKE', "%{$query}%")
                            ->orWhere('city', 'LIKE', "%{$query}%")
                            ->get();
            $results = $results->merge($branches);

            // Search in Autos
            $autos = Auto::where('name', 'LIKE', "%{$query}%")->get();
            $results = $results->merge($autos);

            // Search in MenuItems
            $menuItems = MenuItem::where('name', 'LIKE', "%{$query}%")
                                ->orWhere('slug', 'LIKE', "%{$query}%")
                                ->get();
            $results = $results->merge($menuItems);

            // Search in Files
            $files = File::where('name', 'LIKE', "%{$query}%")
                        ->orWhere('path', 'LIKE', "%{$query}%")
                        ->orWhere('key_words', 'LIKE', "%{$query}%")
                        ->get();
            $results = $results->merge($files);
        }

        return view('search.results', ['results' => $results, 'query' => $query]);
    }
}
