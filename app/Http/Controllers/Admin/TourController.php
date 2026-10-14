<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Booking;
use App\Models\Category;
use App\Models\Destination;
use App\Models\Departure;
use App\Models\Review;
use App\Models\Tour;
use App\Models\TourImage;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

class TourController extends Controller
{
    private function rules(): array
    {
        return [
            'tour_name'           => 'required|string|max:200',
            'category_id'         => 'required|exists:categories,category_id',
            'destination_id'      => 'required|exists:destinations,destination_id',
            'duration_days'       => 'required|integer|min:1',
            'duration_nights'     => 'required|integer|min:0',
            'departure_place'     => 'nullable|string|max:100',
            'adult_price'         => 'required|numeric|min:0',
            'child_price'         => 'required|numeric|min:0',
            'description'         => 'nullable|string',
            'inclusions'          => 'nullable|string',
            'cancellation_policy' => 'nullable|string',
            'status'              => 'required|in:0,1',
            'image_url'           => 'nullable|string|max:255',
        ];
    }

    private function saveImage(Tour $tour, ?string $url): void
    {
        if ($url === null || trim($url) === '') {
            return;
        }
        $img = TourImage::where('tour_id', $tour->tour_id)->where('is_primary', 1)->first();
        if ($img) {
            $img->update(['image_url' => $url]);
        } else {
            TourImage::create(['tour_id' => $tour->tour_id, 'image_url' => $url, 'is_primary' => 1]);
        }
    }

    public function index(Request $request)
    {
        $query = Tour::with(['destination', 'category']);
        if ($kw = trim((string) $request->q)) {
            $query->where('tour_name', 'like', '%' . $kw . '%');
        }
        $tours = $query->orderByDesc('tour_id')->paginate(10)->withQueryString();

        return view('admin.tours.index', compact('tours'));
    }

    public function create()
    {
        return view('admin.tours.create', [
            'tour'         => new Tour(['status' => 1]),
            'categories'   => Category::orderBy('name')->get(),
            'destinations' => Destination::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate($this->rules());
        $tour = Tour::create(Arr::except($data, ['image_url']) + ['created_at' => now()]);
        $this->saveImage($tour, $data['image_url'] ?? null);

        return redirect()->route('admin.tours.edit', $tour)->with('ok', 'Đã thêm tour mới. Hãy thêm lịch khởi hành bên dưới.');
    }

    public function edit(Tour $tour)
    {
        $tour->load('images');
        $departures = $tour->departures()->orderBy('start_date')->get();

        return view('admin.tours.edit', [
            'tour'         => $tour,
            'departures'   => $departures,
            'categories'   => Category::orderBy('name')->get(),
            'destinations' => Destination::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, Tour $tour)
    {
        $data = $request->validate($this->rules());
        $tour->update(Arr::except($data, ['image_url']));
        $this->saveImage($tour, $data['image_url'] ?? null);

        return redirect()->route('admin.tours.index')->with('ok', 'Đã cập nhật tour.');
    }

    public function destroy(Tour $tour)
    {
        $depIds = Departure::where('tour_id', $tour->tour_id)->pluck('departure_id');
        if (Booking::whereIn('departure_id', $depIds)->exists()) {
            return back()->withErrors(['tour' => 'Tour đã có đơn đặt nên không thể xóa. Hãy chuyển trạng thái sang "Ẩn".']);
        }

        DB::transaction(function () use ($tour, $depIds) {
            Departure::whereIn('departure_id', $depIds)->delete();
            Review::where('tour_id', $tour->tour_id)->delete();
            DB::table('favorites')->where('tour_id', $tour->tour_id)->delete();
            $tour->delete(); // tour_itineraries, tour_images tu xoa theo ON DELETE CASCADE
        });

        return redirect()->route('admin.tours.index')->with('ok', 'Đã xóa tour.');
    }
}
