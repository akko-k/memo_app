<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Memo;

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
        $memos = MEMO::select('memos.*')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        return view('create', compact('memos'));
    }

    public function store(Request $request)
    {
        $post = $request->all();
        Memo::insert(['content' => $post['content'], 'user_id' => \Auth::id()]);
        return redirect(route('home'));
    }

    public function edit($id)
    {
        $memos = MEMO::select('memos.*')
            ->where('user_id', '=', \Auth::id())
            ->whereNull('deleted_at')
            ->orderBy('updated_at', 'desc')
            ->get();

        $edit_memo = MEMO::find($id);

        return view('edit', compact('memos', 'edit_memo'));
    }

    public function update(Request $request)
    {
        $post = $request->all();
        Memo::where('id', $post['memo_id'])->update(['content' => $post['content']]);
        return redirect(route('home'));
    }
}
