<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Partner;
use App\Models\Review;
use App\Models\User;
use Carbon\Carbon;
use DateTime;
use DateTimeZone;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Intervention\Image\Facades\Image;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        return view('home');
    }

    public function adminDashboard()
    {
        $users = User::all();
        $events = Event::all();
        $reviews = Review::all();
        $blogs = Blog::all();
        return response([
            'users_count' => $users->count(),
            'events_count' => $events->count(),
            'reviews_count' => $reviews->count(),
            'blogs_count' => $blogs->count(),
        ]);
    }

    public function adminUsers()
    {
        $users = User::get();
        return response()->json([
            'status' => true,
            'users' => $users,
        ]);
    }

    public function adminEvents()
    {
        $events = Event::get();
        $users = User::get(['id', 'name']);
        return response()->json([
            'status' => true,
            'events' => $events,
            'users' => $users,
        ]);
    }

    public function addEvent(Request $request)
    {
//        dd($request->all());
        $validated = $request->validate([
            'title' => 'required|max:255',
            'description' => 'nullable|max:10000',
            'excerpt' => 'nullable|max:1000',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video' => 'nullable|mimes:mp4,mov,ogg,webm|max:512000',
            'organizer' => 'required',
            'location' => 'required|string|max:255',
            'price' => 'nullable|numeric',
            'tags' => 'nullable|array',
            'status' => 'required|in:active,inactive',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);
//        dd($validated);

        $eslug = Str::slug($request->title, '-').'-'.Carbon::now()->format('YmdHis');

        $startDate = Carbon::parse($validated['start_date'])->format('Y-m-d');
        $endDate = $validated['end_date'] ? Carbon::parse($validated['end_date'])->format('Y-m-d') : null;
        $startTime = $request->get('start_time', '00:00');
        $endTime = $request->get('end_time', '00:00');

        $startDateTime = $startDate . ' ' . $startTime;
        $endDateTime = $endDate . ' ' . $endTime;
        $imageUrl = null;
        $videoUrl = null;
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $filename = 'event'.uniqid().'.png';
            $img = Image::make($image->getRealPath())->resize(1200, 675, function ($constraint) {
                $constraint->aspectRatio();
            });
            $imgpath = 'events/images/' . $filename;
            $awsimg = Storage::disk('s3')->put($imgpath, (string)$img->encode());
            if ($awsimg) {
                $imageUrl = $imgpath;
            }

        }
        if ($request->hasFile('video')) {
            $video = $request->file('video');
            $extension = $video->getClientOriginalExtension();
            $filename = 'eventv_' . uniqid() . '.' . $extension;
            $videopath = 'events/videos/' . $filename;
            $awsvideo = Storage::disk('s3')->put($videopath,file_get_contents($video->getRealPath()));
            if ($awsvideo) {
                $videoUrl = $videopath;
//                $videoUrl = Storage::disk('s3')->url($videopath);
            }
        }
        DB::beginTransaction();
        try {
            $event = Event::create([
                'title' => $validated['title'],
                'slug' => $eslug,
                'description' => $validated['description'],
                'excerpt' => $validated['excerpt'],
                'image' => $imageUrl,
                'video' => $videoUrl,
                'organizer' => $validated['organizer'],
                'location' => $validated['location'],
                'price' => $validated['price'],
                'tags' => $validated['tags'] ?? [],
                'status' => $validated['status'],
                'start_date' => $startDate,
                'start_time' => $startTime,
                'end_date' => $endDate,
                'end_time' => $endTime,
                'start_at' => Carbon::parse($startDateTime)->tz('Asia/Dubai'),
                'end_at' => $endDateTime ? Carbon::parse($endDateTime)->tz('Asia/Dubai') : null,
            ]);
            DB::commit();
            return response()->json([
                'status' => true,
                'event' => $event,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }

    }

    public function adminGalleries()
    {
        $galleries = Gallery::get();
        return response()->json([
            'status' => true,
            'galleries' => $galleries,
        ]);
    }

    public function addGallery(Request $request)
    {
        $request->validate([
            'main_image' => 'image|max:2048',
            'photos.*.image' => 'image|max:2048',
            'photos.*.title' => 'nullable|string|max:255',
            'title' => 'required|string|max:255',
        ]);
//        $tz = 'UTC'; // your required location time zone.
//        $timestamp = time();
//        $dt = new DateTime($request->datetime, new DateTimeZone($tz)); //first argument "must" be a string
//        $dt->setTimestamp($timestamp);
//        $datetime = $request->datetime;
//        $cdate = Carbon::parse($datetime);
//        $cnow = $cdate;
        dd(request()->all());
        $gallery = new Gallery();
        $gallery->title = $request->title;
        $gallery->description = $request->description;
        $gallery->datetime = $request->datetime;
        $gallery->save();
        return response()->json([
            'status' => true,
            'gallery' => $gallery,
        ]);
    }

    public function adminBlogs()
    {
        $blogs = Blog::get();
        return response()->json([
            'status' => true,
            'blogs' => $blogs,
        ]);
    }

    public function adminPartners()
    {
        $partners = Partner::get();
        return response()->json([
            'status' => true,
            'partners' => $partners,
        ]);
    }

    public function updatePartner(Request $request)
    {
        $partner = Partner::find($request->id);
        if($request->mtype == "delete"){
            $partner->delete();
            return response()->json([
                'status' => true,
                'message' => 'Partner deleted successfully',
            ]);
        }

        $pimage = $partner->image ?? null;
        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = 'partner_'.uniqid().'.png';
            $img = Image::make($file->getRealPath())->resize(300, 300, function ($constraint) {
                $constraint->aspectRatio();
            });
            $fpath = 'partner/'.$filename;
//            $awsimage = Storage::disk('s3')->putFileAs('partner', $file, $filename,['ACL' => 'public-read']);
            $awsimage = Storage::disk('s3')->put($fpath, (string)$img->encode());
            $pimage = $fpath;
        }
        if ($partner) {
            $partner->update([
                    'name' => $request->name ?? "Partner Name",
                    'image' => $pimage,
                    'description' => $request->description,
                    'package' => $request->package ?? "Bronze",
                    'status' => $request->status ?? "active",
                ]);
            return response()->json([
                'status' => true,
                'partner' => $partner,
            ]);
        } else {
            $partner = Partner::create([
                'name' => $request->name ?? "Partner Name",
                'image' => $pimage,
                'description' => $request->description,
                'package' => $request->package ?? "Bronze",
                'status' => $request->status ?? "active",
            ]);
            return response()->json([
                'status' => true,
                'partner' => $partner,
            ]);
        }
    }

    public function adminReviews()
    {
        $reviews = Review::get();
        return response()->json([
            'status' => true,
            'reviews' => $reviews,
        ]);
    }

    public function updateReview(Request $request)
    {
        $review = Review::find($request->id);
        if($review){
            if($request->mtype == "delete"){
                $review->delete();
                return response()->json([
                    'status' => true,
                    'message' => 'Review deleted successfully',
                ]);
            } else {
                $review->update([
                    'title' => $request->title ?? "Recommended",
                    'review_text' => $request->review_text ?? "Great Service from DUBSB",
                    'rating' => $request->rating ?? 5.0,
                    'status' => $request->status ?? 'pending',
                    'user_id' => $request->user_id ?? 1,
                ]);
                return response()->json([
                    'status' => true,
                    'review' => $review,
                ]);
            }
        } else {
            $review = Review::create([
                'title' => $request->title ?? "Recommended",
                'review_text' => $request->review_text ?? "Great Service from DUBSB",
                'rating' => $request->rating ?? 5.0,
                'status' => $request->status ?? 'pending',
                'user_id' => $request->user_id ?? 1,
            ]);
            return response()->json([
                'status' => true,
                'review' => $review,
            ]);
        }
    }
}
