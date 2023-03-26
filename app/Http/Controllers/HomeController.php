<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;
use App\Models\Tag;
use App\Models\MemoTag;
use DB;

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
        $memos = Memo::user()->active()->orderBy('updated_at', 'desc')->get();

        return view('create', compact('memos'));
    }

    public function store(Request $request)
    {
        $post = $request->all();
        DB::transaction(function () use ($post) {
            $memo_id = Memo::insertGetId(['content' => $post['content'], 'user_id' => \Auth::id()]);
            $tag_exists = Tag::where('name', '=', $post['new_tag'])->where('user_id', '=', \Auth::id())->exists();
            if (!empty($post['new_tag']) && !$tag_exists) {
                $tag_id = Tag::insertGetId(['name' => $post['new_tag'], 'user_id' => \Auth::id()]);
                MemoTag::insert(['memo_id' => $memo_id, 'tag_id' => $tag_id]);
            }
        });
        return redirect(route('home'));
    }

    public function edit($id)
    {
        $memos = Memo::select('memos.*')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $edit_memo = Memo::find($id);

        return view('edit', compact('memos', 'edit_memo'));
    }

    public function update(Request $request)
    {
        $post = $request->all();
        Memo::where('id', $post['memo_id'])->update(['content' => $post['content']]);
        return redirect(route('home'));
    }

    public function destroy(Request $request)
    {
        $post = $request->all();
        Memo::where('id', $post['memo_id'])->update(['deleted_at' => date("Y-m-d H:i:s", time())]);
        return redirect(route('home'));
    }
}
