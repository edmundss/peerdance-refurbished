<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use App\Activity;
use App\Transformers\ActivityTransformer;

use App\Models\Dance;
use App\Models\Discussion;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $session_owner = Auth::user();

        $keywords = config('constants.keywords');

        $sorted_steps = [];
            if ($session_owner) {
            $tracked_steps = DB::table('step_user')
                ->where('user_id', $session_owner->id)
                ->where('relation_type', 2)
                ->leftJoin('steps' , 'step_user.step_id', '=', 'steps.id')
                ->leftJoin('dances', 'steps.dance_id', '=', 'dances.id')
                ->select([
                    'steps.title AS step',
                    'steps.id',
                    'dances.title AS dance',
                ])
                ->get();

            foreach ($tracked_steps as $s) {
                $sorted_steps[$s->dance][] = $s;
            }
        }

        $params = [
            'session_owner' => $session_owner,
            'page_title' => 'Learn. Dance. Share. Teach.',
            'keywords' => implode(",", $keywords),
            'appclass' => 'timeline-page notes-app',
            'sorted_steps' => $sorted_steps,
            'top_dances' => Dance::top_dances(),
            'last_discussions' => Discussion::orderBy('last_reply_at', 'DESC')->take(3)->get(),
            'toggle_right' => (Auth::check())?'':'toggle-right',
        ];

        return view('dashboard.index')->with($params);
    }

    public static function get_timeline_elements(Request $request)
    {
        $activities = Activity::orderBy('created_at', 'DESC')
            ->skip($request->offset)
            ->take(5)
            ->get();

        $response_html = '';

        foreach ($activities as $a) {
            $transformed_activity =  fractal($a, new ActivityTransformer())->toArray();

            $response_html = $response_html . view($transformed_activity['data']['view'])->with($transformed_activity);
        }

        return $response_html;
    }
}
