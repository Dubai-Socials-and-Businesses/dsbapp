<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Partner;
use App\Models\Photo;
use App\Models\Review;
use App\Models\User;
use App\Models\Video;
use Carbon\Carbon;
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
        $galleries = Gallery::all();
        $partners = Partner::all();
        return response([
            'user' => auth()->user(),
            'users_count' => $users->count(),
            'events_count' => $events->count(),
            'reviews_count' => $reviews->count(),
            'blogs_count' => $blogs->count(),
            'galleries_count' => $galleries->count(),
            'partners_count' => $partners->count(),
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
        $events = Event::withCount('attendees')->get();
        $users = User::get(['id', 'name']);
        return response()->json([
            'status' => true,
            'events' => $events,
            'users' => $users,
        ]);
    }

    public function addEvent(Request $request)
    {
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

        $eslug = Str::slug($request->title, '-').'-'.Carbon::now()->format('YmdHis');
        $startDateTime = Carbon::parse($validated['start_date'] . ' ' . ($request->get('start_time', '00:00:00')), 'Asia/Dubai');
        $endDateTime = $validated['end_date'] ?
            Carbon::parse($validated['end_date'] . ' ' . ($request->get('end_time', '00:00:00')), 'Asia/Dubai') : null;

        $startDate = $startDateTime->format('Y-m-d');
        $startTime = $startDateTime->format('H:i:s');
        $endDate = $endDateTime?->format('Y-m-d');
        $endTime = $endDateTime?->format('H:i:s');

//        $startDate = Carbon::parse($validated['start_date'])->format('Y-m-d');
//        $endDate = $validated['end_date'] ? Carbon::parse($validated['end_date'])->format('Y-m-d') : null;
//        $startTime = $request->get('start_time', '00:00');
//        $endTime = $request->get('end_time', '00:00');
//
//        $startDateTime = $startDate . ' ' . $startTime;
//        $endDateTime = $endDate . ' ' . $endTime;
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
                'start_at' => $startDateTime,
                'end_at' => $endDateTime,
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

    public function getAdminEventById($id)
    {
        $event = Event::with('attendees')->withCount('attendees')->where('id', $id)->first();
        $users = User::get(['id', 'name']);
        return response()->json([
            'status' => true,
            'event' => $event,
            'users' => $users,
        ]);
    }

    public function editEvent(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:events,id',
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

        $event = Event::where('id', $validated['id'])->first();
        $startDateTime = Carbon::parse($validated['start_date'] . ' ' . ($request->get('start_time', '00:00:00')), 'Asia/Dubai');
        $endDateTime = $validated['end_date'] ?
            Carbon::parse($validated['end_date'] . ' ' . ($request->get('end_time', '00:00:00')), 'Asia/Dubai') : null;

        $startDate = $startDateTime->format('Y-m-d');
        $startTime = $startDateTime->format('H:i:s');
        $endDate = $endDateTime?->format('Y-m-d');
        $endTime = $endDateTime?->format('H:i:s');

        $imageUrl = $event->image ?? null;
        $videoUrl = $event->video ?? null;
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
            }
        }

        DB::beginTransaction();
        try {
            $event->update([
                'title' => $validated['title'],
                'description' => $validated['description'],
                'excerpt' => $validated['excerpt'],
                'image' => $imageUrl,
                'video' => $videoUrl,
                'organizer' => $validated['organizer'],
                'location' => $validated['location'],
                'price' => $validated['price'],
                'tags' => $validated['tags'],
                'status' => $validated['status'],
                'start_date' => $startDate,
                'start_time' => $startTime,
                'end_date' => $endDate,
                'end_time' => $endTime,
                'start_at' => $startDateTime,
                'end_at' => $endDateTime,
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
        $galleries = Gallery::with('photos','video')->get();
        return response()->json([
            'status' => true,
            'galleries' => $galleries,
        ]);
    }

    public function addGallery(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|max:10000',
            'main_image' => 'nullable|image|max:2048',
            'gdate' => 'nullable|date',
            'photos.*.image' => 'image|max:2048',
            'photos.*.title' => 'nullable|string|max:255',
            'video' => 'nullable',
            'video.title' => 'nullable|string|max:255',
            'video.video_url' => 'nullable|file|mimes:mp4,mov,ogg,webm|max:512000',
            'video.youtube' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $mimagePath = null;
            if($request->hasFile('main_image')) {
                $image = $request->file('main_image');
                $filename = 'main_'.uniqid().'.png';
                $imgpath = 'gallery/' . $filename;
                $img = Image::make($image->getRealPath())
                    ->resize(1200, 675, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                $success = Storage::disk('s3')->put($imgpath, (string)$img->encode());
                if ($success) {
                    $mimagePath = $imgpath;
                }
            }
            $gallery = Gallery::create([
                'title' => $validated['title'],
                'main_image' => $mimagePath,
                'description' => $validated['description'],
                'gdate' => $validated['gdate'],
            ]);
            if(!empty($validated['photos'])) {
                $this->processPhotos($gallery,$validated['photos']);
            }
            if(!empty($validated['video'])) {
                $videoUrl = null;
                if ($request->hasFile('video.video_url')) {
                    $video = $request->file('video.video_url');
                    $extension = $video->getClientOriginalExtension();
                    $filename = 'video_' . uniqid() . '.' . $extension;
                    $videopath = 'gallery/' . $filename;
                    $awsvideo = Storage::disk('s3')->put($videopath,file_get_contents($video->getRealPath()));
                    if ($awsvideo) {
                        $videoUrl = $videopath;
                    }
                }
                Video::create([
                    'video_url' => $videoUrl,
                    'gallery_id' => $gallery->id,
                    'title' => $validated['video']['title'] ?? $gallery->title,
                    'youtube' => $validated['video']['youtube'] ?? null,
                ]);
            }
            DB::commit();
            return response()->json([
                'status' => true,
                'gallery' => $gallery->load('photos'),
                'message' => "Gallery added successfully",
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getAdminGalleryByid($id){
        $gallery = Gallery::with('photos','video')->where('id',$id)->first();
        return response()->json([
            'status' => true,
            'gallery' => $gallery->load('photos'),
        ]);
    }

    public function editGallery(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|exists:galleries,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|max:10000',
            'main_image' => 'nullable|image|max:2048',
            'gdate' => 'nullable|date',
            'gphotos' => 'array',
            'gphotos.*.id' => 'required|exists:photos,id',
            'gphotos.*.title' => 'nullable|string|max:255',
            'gphotos.*.nimage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'photos' => 'array',
            'photos.*.title' => 'nullable|string|max:255',
            'photos.*.nimage' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'video.id' => 'required|exists:videos,id',
            'video.title' => 'nullable|string|max:255',
            'video.video_url' => 'nullable|file|mimes:mp4,mov,ogg,webm|max:512000',
            'video.youtube' => 'nullable|string|max:255',
        ]);

        DB::beginTransaction();
        try {
            $gallery = Gallery::where('id',$validated['id'])->first();
            $mImagePath = $gallery->main_image ?? null;
            if($request->hasFile('main_image')) {
                $image = $request->file('main_image');
                $filename = 'main_'.uniqid().'.png';
                $imgpath = 'gallery/' . $filename;
                $img = Image::make($image->getRealPath())
                    ->resize(1200, 675, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                $success = Storage::disk('s3')->put($imgpath, (string)$img->encode());
                if ($success) {
                    $mImagePath = $imgpath;
                }
            }
            $gallery->update([
                'title' => $validated['title'],
                'main_image' => $mImagePath,
                'description' => $validated['description'],
                'gdate' => $validated['gdate'],
            ]);
            if(!empty($validated['gphotos'])) {
                $existingPhotoIds = $gallery->photos()->pluck('id')->toArray();
                $submittedPhotoIds = array_column($validated['gphotos'] ?? [], 'id');
                $idsToDelete = array_diff($existingPhotoIds, $submittedPhotoIds);
                if (!empty($idsToDelete)) {
                    $photosToDelete = Photo::whereIn('id', $idsToDelete)->get();
                    foreach ($photosToDelete as $photoToDelete) {
                        $photoToDelete->delete();
                    }
                }
                foreach($validated['gphotos'] as $gphoto) {
                    $existphotoData = Photo::where('id',$gphoto['id'])->first();
                    $gphotoPath = $existphotoData->image ?? null;
                    if(isset($gphoto['nimage']) && $gphoto['nimage'] instanceof \Illuminate\Http\UploadedFile){
                        $image = $gphoto['nimage'];
                        $filename = 'photo_'.uniqid().'.png';
                        $imgpath = 'gallery/' . $filename;
                        $img = Image::make($image->getRealPath())->resize(1200, 675, function ($constraint) {
                                $constraint->aspectRatio();
                            });
                        $success = Storage::disk('s3')->put($imgpath, (string)$img->encode());
                        if ($success) {
                            $gphotoPath = $imgpath;
                        }
                    }
                    if($existphotoData) {
                        $existphotoData->update([
                            'title' => $gphoto['title'] ?? $gallery->title,
                            'image' => $gphotoPath,
                            'gallery_id' => $gallery->id,
                        ]);
                    }
                }
            }
            if(!empty($validated['photos'])) {
                foreach($validated['photos'] as $photo) {
                    $photoPath = null;
                    if(isset($photo['nimage']) && $photo['nimage'] instanceof \Illuminate\Http\UploadedFile){
                        $image = $photo['nimage'];
                        $filename = 'photo_'.uniqid().'.png';
                        $imgpath = 'gallery/' . $filename;
                        $img = Image::make($image->getRealPath())->resize(1200, 675, function ($constraint) {
                            $constraint->aspectRatio();
                        });
                        $success = Storage::disk('s3')->put($imgpath, (string)$img->encode());
                        if ($success) {
                            $photoPath = $imgpath;
                        }
                    }
                    Photo::create([
                        'title' => $photo['title'] ?? $gallery->title,
                        'image' => $photoPath,
                        'gallery_id' => $gallery->id,
                    ]);
                }
            }

            $video = Video::findOrFail($validated['video']['id']);
            $videoUrl = $video->video_url ?? null;
            if($request->hasFile('video.video_url')) {
                $video = $request->file('video.video_url');
                $extension = $video->getClientOriginalExtension();
                $filename = 'video_' . uniqid() . '.' . $extension;
                $videopath = 'gallery/' . $filename;
                $awsvideo = Storage::disk('s3')->put($videopath,file_get_contents($video->getRealPath()));
                if ($awsvideo) {
                    $videoUrl = $videopath;
                }
            }
            $video->update([
                'video_url' => $videoUrl,
                'gallery_id' => $gallery->id,
                'title' => $validated['video']['title'] ?? $gallery->title,
                'youtube' => $validated['video']['youtube'] ?? null,
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Gallery updated successfully!',
                'gallery' => $gallery,
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function processPhotos(Gallery $gallery,array $photos)
    {
        $photoData = collect($photos)
            ->filter(fn($photo) => isset($photo['image']) && $photo['image']->isValid())
            ->map(function($photo) use ($gallery){
            $image = $photo['image'];
            $filename = 'photo_'.uniqid().'.png';
            $imgpath = 'gallery/' . $filename;
            $img = Image::make($image->getRealPath())
                ->resize(1200, 675, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $success = Storage::disk('s3')->put($imgpath, (string)$img->encode());
                if (!$success) {
                return null;
            }
            return [
                'title' => $photo['title'] ?? $gallery->title,
                'image' => $imgpath,
                'gallery_id' => $gallery->id,
            ];
        })->filter()->toArray();
        if (!empty($photoData)) {
            $gallery->photos()->createMany($photoData);
        }
    }

    public function adminBlogs()
    {
        $blogs = Blog::get();
        return response()->json([
            'status' => true,
            'blogs' => $blogs,
        ]);
    }

    public function addBlog(Request $request)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'excerpt' => 'nullable|string',
            'description' => 'nullable|string',
            'tags'=>'nullable|array',
            'status' => 'required|in:active,inactive',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
        ]);
        DB::beginTransaction();
        try {
            $imageUrl = null;
            if($request->hasFile('image')) {
                $image = $request->file('image');
                $filename = 'blog_'.uniqid().'.png';
                $imgpath = 'blogs/' . $filename;
                $img = Image::make($image->getRealPath())->resize(1200, 675, function ($constraint) {
                    $constraint->aspectRatio();
                });
                $success = Storage::disk('s3')->put($imgpath, (string)$img->encode());
                if ($success) {
                    $imageUrl = $imgpath;
                }
            }
            $blog = Blog::create([
                'title' => $validatedData['title'],
                'slug' => Str::slug($validatedData['title']).time(),
                'excerpt' => $validatedData['excerpt'],
                'description' => $validatedData['description'],
                'image' => $imageUrl,
                'status' => $validatedData['status'],
                'tags' => $validatedData['tags'] ?? null,
                'user_id' => 2,
            ]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Blog created successfully!',
                'blog' => $blog,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
    }

    public function getAdminBlogByid($id)
    {
        $blog = Blog::where('id',$id)->first();
        if($blog) {
            return response()->json([
                'success' => true,
                'blog' => $blog,
                'message' => 'Blog retrieved successfully!',
            ]);
        } else {
            return response()->json(['success' => false, 'message' => 'Blog not found!']);
        }
    }

    public function editBlog(Request $request)
    {
        $validatedData = $request->validate([
            'id' => 'required|exists:blogs,id',
            'title' => 'required|string|max:255',
            'status' => 'required|in:active,inactive',
        ]);
        DB::beginTransaction();
        try {
            $blog = Blog::where('id',$validatedData['id'])->first();
            if($blog) {
                $imageUrl = $blog->image;
                if($request->hasFile('nimage')) {
                    $image = $request->file('nimage');
                    $filename = 'blog_'.uniqid().'.png';
                    $imgpath = 'blogs/' . $filename;
                    $img = Image::make($image->getRealPath())->resize(1200, 675, function ($constraint) {
                        $constraint->aspectRatio();
                    });
                    $success = Storage::disk('s3')->put($imgpath, (string)$img->encode());
                    if ($success) {
                        $imageUrl = $imgpath;
                    }
                }
                $blog->update([
                    'title' => $validatedData['title'],
                    'description' => $request->get('description'),
                    'excerpt' => $request->get('excerpt'),
                    'tags' => $request->get('tags') ?? null,
                    'image' => $imageUrl,
                    'status' => $validatedData['status'],
                ]);
            }
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'Blog updated successfully!',
                'blog' => $blog,
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => false,
                'message' => $e->getMessage(),
            ]);
        }
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
                    'reviewer_name' => $request->reviewer_name ?? 'Alex John',
                    'review_date' => $request->review_date ?? null,
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
                'reviewer_name' => $request->reviewer_name ?? 'Alex John',
                'review_date' => $request->review_date ?? null,
                'user_id' => $request->user_id ?? 1,
            ]);
            return response()->json([
                'status' => true,
                'review' => $review,
            ]);
        }
    }
}
