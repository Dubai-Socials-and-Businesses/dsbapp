<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Event;
use App\Models\Gallery;
use App\Models\Package;
use App\Models\Partner;
use App\Models\Review;
use Illuminate\Http\Request;

class FrontController extends Controller
{
    public function homeApis(){
        $partners = Partner::all();
        $reviews = Review::Where('status','verified')->get();
        $fevent = Event::withCount('attendees')->with('attendees')->whereDate('start_date', '>=', today())
            ->orderBy('start_date', 'ASC')
            ->first();
        return response()->json([
            'success' => true,
            'partners' => $partners,
            'reviews' => $reviews,
            'fevent' => $fevent
        ]);
    }

    public function blogsApi()
    {
        $blogs = Blog::orderBy('created_at','desc')->get();
        return response()->json([
            'success' => true,
            'blogs' => $blogs
        ]);
    }

    public function getBlogBySlug($slug)
    {
        $blog = Blog::where('slug',$slug)->first();
        if($blog){
            return response()->json([
                'success' => true,
                'message'=>'Blog found',
                'blog' => $blog
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Blog not found'
            ]);
        }
    }

    public function eventsApi()
    {
        $events = Event::withCount('attendees')->with('attendees')->orderBy('start_date','DESC')->get();
        return response()->json([
            'success' => true,
            'events' => $events
        ]);
    }

    public function getEventBySlug($slug)
    {
        $event = Event::withCount('attendees')->with('attendees')->where('slug',$slug)->first();
        if($event){
            return response()->json([
                'success' => true,
                'message'=>'Event found',
                'event' => $event
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Event not found'
            ]);
        }
    }

    public function galleryApi()
    {
        $galleries = Gallery::with('photos','video','reels')->get();
        return response()->json([
            'success' => true,
            'galleries' => $galleries
        ]);
    }

    public function partnersApi()
    {
        $partners = Partner::get();
        return response()->json([
            'success' => true,
            'partners' => $partners
        ]);
    }

    public function getPartnerById($id)
    {
        $partner = Partner::where('id',$id)->first();
        if($partner){
            return response()->json([
                'success' => true,
                'message'=>'Partner found',
                'partner' => $partner
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Partner not found'
            ]);
        }
    }

    public function packagesApi()
    {
        $packages = Package::where('status','=','active')->orderBy('price','ASC')->get();
        return response()->json([
            'success' => true,
            'packages' => $packages
        ]);
    }

    public function reviewsApi()
    {
        $reviews = Review::Where('status','verified')->get();
        return response()->json([
            'success' => true,
            'reviews' => $reviews,
            'reviews_count' => $reviews->count() ?? null
        ]);
    }

   public function homePage(){
       return view('welcome');
   }

   public function gallery()
   {
       return view('gallery');
   }

   public function events()
   {
       return view('events');
   }

   public function about()
   {
       return view('about');
   }

   public function contact()
   {
       return view('contact');
   }

   public function blogs()
   {
       return view('blogs');
   }
}
