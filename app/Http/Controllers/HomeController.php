<?php
namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Destination;
use App\Models\Tour;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index(Request $request)
    {
        $query = Tour::with(['destination', 'category', 'images'])->where('status', 1);

        if ($kw = trim((string) $request->q)) {
            $query->where('tour_name', 'like', '%' . $kw . '%');
        }
        if ($request->filled('destination')) {
            $query->where('destination_id', $request->destination);
        }
        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }
        if ($request->filled('max_price')) {
            $query->where('adult_price', '<=', (float) $request->max_price);
        }

        $tours = $query->orderByDesc('tour_id')->paginate(9)->withQueryString();

        return view('home', [
            'tours'        => $tours,
            'destinations' => Destination::orderBy('name')->get(),
            'categories'   => Category::orderBy('name')->get(),
        ]);
    }

    public function show(Tour $tour)
    {
        abort_if($tour->status != 1, 404);

        $tour->load(['destination', 'category', 'images', 'itineraries']);
        $departures = $tour->departures()
            ->where('status', 'OPEN')
            ->where('available_seats', '>', 0)
            ->whereDate('start_date', '>=', now()->toDateString())
            ->orderBy('start_date')
            ->get();
        $reviews = $tour->reviews()->with('user')->where('status', 1)->orderByDesc('review_id')->get();

        return view('tours.show', compact('tour', 'departures', 'reviews'));
    }
}
