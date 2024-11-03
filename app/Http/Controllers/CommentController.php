<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;
use Auth;
use DB;
use App\User;

class CommentController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
            array(
                'comment' => array('required'),
                'parent_id' => array('required'),
                'parent_class' => array('required'),
            )
        );
        
        $parent_id = $request->input('parent_id');
        $parent_class = $request->input('parent_class');

        $comment = new Comment();
        // return '<pre>'. var_dump($comment);
        $comment->comment = strip_tags($request->input('comment'));
        $comment->user_id = Auth::user()->id;
        $comment->parent_id = $parent_id;
        $comment->parent_class = $parent_class;
        //return '<pre>'. var_dump($comment);
        
        
        $comment->save();
        return redirect()->back()->withMessage('Komentārs ir saglabāts!');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function show(Comment $comment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function edit(Comment $comment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Comment $comment)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Comment  $comment
     * @return \Illuminate\Http\Response
     */
    public function destroy(Comment $comment)
    {
        //
    }


    public static function save($parent_class, $parent_id, $comment)
    {
        $c = new Comment();

        $c->comment = strip_tags($comment);
        $c->user_id = Auth::user()->id;
        $c->parent_id = $parent_id;
        $c->parent_class = $parent_class;

        $c->save();
    }

    public static function formated_portion($take=10, $skip=0, $parent_class="Dashboard", $parent_id=null)
    {
        
        $comments = DB::table('comments')
            ->join('users', 'comments.user_id', '=', 'users.id')
            ->where('comments.parent_class', $parent_class)
            ->select(
                'user_id', 
                'comment', 
                DB::raw('DATE_FORMAT(TIME('. DB::getTablePrefix().'comments.created_at), "%k:%i") as time'),
                DB::raw('DATE('. DB::getTablePrefix().'comments.created_at) as date'),
                'name'
            )
            ->orderBy('comments.created_at', 'desc')
            ->skip($skip)
            ->take($take)
            ->get();

        foreach ($comments as $c) {

            //format time

            //set avatar
            /* before using avatars i need to sort out pics
            if ($c->user->has_avatar)
            {
                $c->avatar = asset('img/uploads/avatars/' . $c->user->id . '/xs' . $c->user->avatar_ext);
            }*/

            //Define action
            $c->action = '';



            //get user's avatar
            $user = User::find($c->user_id);
            $c->avatar = $user->getAvatar('thumb');

            // turn urls into links
            // The Regular Expression filter
            $reg_exUrl = "/(http|https|ftp|ftps)\:\/\/[a-zA-Z0-9\-\.]+\.[a-zA-Z]{2,3}(\/\S*)?/";
            //find links
            preg_match_all($reg_exUrl, $c->comment, $url);
            //replace each link with url
            $image = false;
            foreach ($url[0] as $u) {
                $ext = explode('.', $u);
                $ext = $ext[count($ext)-1];
                $ext = strtolower($ext);


                if (strpos($u,'youtube') !== false) 
                {
                    $c->comment = str_replace([$u], [''], $c->comment);
                    parse_str( parse_url( $u, PHP_URL_QUERY ), $array_of_vars );
                    $youtube_code = $array_of_vars['v'];
                    $c->comment = $c->comment . '
                    <div class="embed-responsive embed-responsive-16by9">
                      <iframe class="embed-responsive-item" src="https://www.youtube.com/embed/'.$youtube_code.'"></iframe>
                     </div>';
                }
                elseif($ext == 'jpeg' || $ext == 'jpg' || $ext == 'png' || $ext == 'gif')
                {
                    $c->comment = str_replace([$u], [''], $c->comment);
                    if(!$image)
                    {
                        $c->comment = $c->comment . '<br>';
                        $image = true;
                    }
                    $c->comment = $c->comment . '<a href="'.$u.'"><img class="margin" style="max-height:100px" src="' . $u . '"/></a>';

                } else {
                    $c->comment = str_replace([$u], ['<a href="' . $u . '">' . parse_url($u, PHP_URL_HOST) . '</a>'], $c->comment);
                }

            }

        }

        return $comments;
    }

    public function CommentFeed(Request $request){
        $skip = intval($request->input('skip'));
        $comments = CommentController::formated_portion(5, $skip);

        //return json_encode($comments, JSON_PRETTY_PRINT)->header('Content-Type', "application/json");
        return response()->json($comments)->header('Content-Type', "application/json");
    }
}
