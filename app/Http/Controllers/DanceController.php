<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Intervention\Image\ImageManagerStatic as Image;
use Illuminate\Support\Collection;

//FORUMS STUFF START
use DevDojo\Chatter\Helpers\ChatterHelper as Helper;
use DevDojo\Chatter\Models\Models;
use DevDojo\Chatter\Models\Category;
//FORUMS STUFF END

use File;
use GuzzleHttp;
use Auth;

use App\Models\Dance;
use App\Models\DanceFamily;

class DanceController extends Controller
{

     public function __construct()
    {
        $this->middleware('auth')->only(['create', 'store', 'update', 'destroy', 'toggle']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function index()
    {
        $session_owner = Auth::user();
        $keywords = config('constants.keywords');

        if(Auth::check()) {
            $content_dances = $session_owner->dances;
        } else {
            $content_dances = Dance::withCount('users')
            ->orderBy('users_count', 'desc')
            ->take(8)
            ->get();

        }

        $params = array(
            'page_title' => 'Dance Library',
            'keywords' => implode(",", $keywords),
            'session_owner' => $session_owner,
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>route('dances.index'), 'title'=>'Dances'],
            ),
            'dances' => Dance::orderBy('title')->get(),
            'content_dances' => $content_dances,
        );

        return view('dances.index')->with($params);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $session_owner = Auth::user();
        $dance_families = new Collection([null => 'None']);

        $params = array(
            'page_title' => 'Create New Dance',
            'session_owner' => $session_owner,
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>route('dances.index'), 'title'=>'Dances'],
                ['url'=>route('dances.create'), 'title'=>'Create'],
            ),
            'dance_families' => $dance_families->union(DanceFamily::orderBy('name')->pluck('name', 'id')),
        );

        return view('dances.create')->with($params);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate(
            $request,
            [
                'title' => 'required',
                'cover' => 'required',
                'logo' => 'required',
            ]
        );

        $dance = Dance::create([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'dance_group_id' => $request->input('dance_group_id'),
            'dance_family_id' => $request->input('dance_family_id'),
            'created_by' => Auth::id(),
        ]);

        Category::create([
            'parent_id' => null,
            'order' => 1,
            'name' => $dance->title,
            'color' => '#ec407a',
            'slug' => str_slug($dance->title, "_"),
            'subject_class' => 'Dance',
            'subject_id' => $dance->id,
        ]);

        $logo = $request->file('logo');
        $cover = $request->file('cover');
        $path = 'image/dance/' . $dance->id . '/';
        File::makeDirectory($path, 0775, true, true);

        $img = Image::make($logo);
        $pic = $img->fit(128, 128);
        $pic->save($path . 'logo.jpg', 80);

        $img = Image::make($cover);
        $img->save($path . 'cover.jpg', 80);

        return redirect()->route('dance.show', $dance->id)->withMessage('WOW! You created a new dance!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Dance  $dance
     * @return \Illuminate\Http\Response
     */
    public function show(Dance $dance)
    {
        //return $dance->family;


        // START FORUM STUFF
        $pagination_results = 5;

        $discussions = Models::discussion()
            ->with('user')
            ->with('post')
            ->with('postsCount')
            ->with('category')
            ->orderBy(config('chatter.order_by.discussions.order'), config('chatter.order_by.discussions.by'))
            ->where('chatter_category_id', '=', $dance->forum_category->id)
            ->paginate($pagination_results);

        $categories = Models::category()->get();
        // $categoriesMenu = Helper::categoriesMenu(array_filter($categories->toArray(), function ($item) {
        //     return $item['parent_id'] === null;
        // }));

        $chatter_editor = config('chatter.editor');

        if ($chatter_editor == 'simplemde') {
            // Dynamically register markdown service provider
            \App::register('GrahamCampbell\Markdown\MarkdownServiceProvider');
        }
        // END FORUM STUFF


        $session_owner = Auth::user();
        $spotify_playback_devices = [];
        $keywords = config('constants.keywords');
        $keywords[] = $dance->title;

        $token_expired = null;

        if(Auth::check()) {
            if($session_owner->spotify_refresh_token) {
                $attempts = 0;

                do {
                    try {
                        //return 'yay';
                        $request_params = [
                            'headers' => [
                                'Authorization' =>  ['Bearer ' . $session_owner->spotify_token],
                                "Content-Type" => "application/x-www-form-urlencoded",
                            ]
                        ];
                        $client = new GuzzleHttp\Client();
                        $res = $client->get('https://api.spotify.com/v1/me/player/devices', $request_params);

                        $result = json_decode($res->getBody(), true);

                        foreach ($result['devices'] as $r) {
                            $spotify_playback_devices[$r['id']] = $r['name'];
                        };

                    } catch (\Exception $e) {
                        //echo $e->getMessage();
                        $token_expired = ['Your spotify token expired. Refresh page to renew it.'];
                        $token_request_params = [
                            'headers' => [
                                'Authorization' =>  ['Basic ' . base64_encode(env('SPOTIFY_KEY') . ':' . env('SPOTIFY_SECRET'))],
                                "Content-Type" => "application/x-www-form-urlencoded",
                            ],
                            'form_params' => [
                                'grant_type' => 'refresh_token',
                                'refresh_token' => $session_owner->spotify_refresh_token,
                            ]
                        ];

                        $client = new GuzzleHttp\Client();
                        $res = $client->post('https://accounts.spotify.com/api/token', $token_request_params);
                        $response_body = $res->getBody();

                        $data = json_decode($response_body);

                        $session_owner->update([
                            'spotify_token' => $data->access_token,
                        ]);
                    }
                    break;

                } while($attempts < 5);

            }

        }

        $chatter_editor = config('chatter.editor');

        if ($chatter_editor == 'simplemde') {
            // Dynamically register markdown service provider
            \App::register('GrahamCampbell\Markdown\MarkdownServiceProvider');
        }


        $params = array(
            'page_title' => 'Dance: ' . $dance->title,
            'keywords' => implode(",", $keywords),
            'session_owner' => $session_owner,
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>route('dances.index'), 'title'=>'Dances'],
                ['url'=>route('dances.show', $dance), 'title'=>$dance->title],
            ),
            'dance' => $dance,
            'parent_id' => $dance->id,
            'parent_class' => 'Dance',
            'comments' => $dance->comments,
            'spotify_playback_devices' => $spotify_playback_devices,
            'og_image' => asset('image/dance/' .$dance->id. '/cover.jpg'),
            'header_size' => 'header-xl  profile-header',
            'appclass' => 'page-profile',
            'discussions' => $discussions,
            'chatter_editor' => $chatter_editor,
            'categories' => $categories,
        );

        return view('dances.show')->with($params)->withErrors($token_expired);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Dance  $dance
     * @return \Illuminate\Http\Response
     */
    public function edit(Dance $dance)
    {
        $session_owner = Auth::user();
        $dance_families = new Collection([null => 'None']);


        $params = array(
            'page_title' => 'Edit ' . $dance->title,
            'session_owner' => $session_owner,
            'breadcrumb' => array(
                ['url'=>route('home'), 'title'=>'Home'],
                ['url'=>route('dance.index'), 'title'=>'Dances'],
                ['url'=>route('dance.show', $dance), 'title'=>$dance->title],
                ['url'=>route('dance.edit', $dance), 'title'=>'Edit'],
            ),
            'dance' => $dance,
            'dance_families' => $dance_families->union(DanceFamily::orderBy('name')->pluck('name', 'id')),
        );

        return view('dances.edit')->with($params);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Dance  $dance
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Dance $dance)
    {
        $session_owner = Auth::user();

        $this->validate(
            $request,
            [
                'title' => 'required',
            ]
        );

        $dance->update([
            'title' => $request->input('title'),
            'description' => $request->input('description'),
            'dance_group_id' => $request->input('dance_group_id'),
            'dance_family_id' => $request->input('dance_family_id'),
            'updated_by' => Auth::id(),
        ]);

        $logo = $request->file('logo');
        $cover = $request->file('cover');

        $path = 'image/dance/' . $dance->id . '/';
        File::makeDirectory($path, 0775, true, true);

        if($logo) {
            $img = Image::make($logo);
            $pic = $img->fit(128, 128);
            $pic->save($path . 'logo.jpg', 80);
        }

        if($cover) {
            $img = Image::make($cover);
            $img->save($path . 'cover.jpg', 80);
        }

        return redirect()->route('dance.show', $dance->id)->withMessage('The dance was updated!');
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Dance  $dance
     * @return \Illuminate\Http\Response
     */
    public function destroy(Dance $dance)
    {
        //
    }

    public function toggle($id)
    {
        $dance = Dance::findOrFail($id);
        $session_owner = Auth::user();

        if($dance->users->contains($session_owner->id)) {
            $dance->users()->detach([$session_owner->id]);
            $message = 'Tavas attiecības ar ' . $dance->title . ' ir sekmīgi pārtrauktas!';
        }else{
            $dance->users()->attach([$session_owner->id]);
            $message = $dance->title . ' tagad ir Tavā deju repertuārā!';
        }

        return redirect()->back()->withMessage($message);
    }
}
