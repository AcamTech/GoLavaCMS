<?php

namespace App\Http\Controllers;

use App\Page;
use App\Blog;
use App\User;
use App\Mainmenu;
use App\Submenu;
use App\Roles\Role;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class NavbarController extends Controller
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
    //add navbar
    public function addnavbar()
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_editor');

        if($getRole >= $min_level)
        {
            $allpage = Page::all(['title', 'slug', 'id']);
            $allblog = Blog::all(['title', 'slug']);
            $allmainmenu = Mainmenu::all();

            return view('navbar/addnavbar', ['role' => $getRole])->with(compact('allpage', $allpage, 'allblog', $allblog, 'allmainmenu', $allmainmenu));
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //store menu to main menu
    public function storenavbarmainmenu(Request $req)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_editor');

        if($getRole >= $min_level)
        {

            $validator = Validator::make(request()->all(), [
                'mainmenu_name' => 'required',
                'mainmenu_link' => 'required',
            ]);

            if($validator->passes())
            {
                if(!is_null($req))
                {
                  $menu = new Mainmenu;
                  $menu->mainmenu_name = $req->mainmenu_name;
                  $menu->mainmenu_link = $req->mainmenu_link;
                  $menu->save();

                  $req->session()->flash('success', 'Successfully added!');
                  return redirect()->action('NavbarController@addnavbar');
                }
                else
                {
                  $req->session()->flash('warning', 'Error, menu is empty! Please select menu.');
                  return redirect()->action('NavbarController@addnavbar');
                }
            }
            else
            {
              $req->session()->flash('warning', 'Error, menu is empty! Please select menu.');
              return redirect()->action('NavbarController@addnavbar');

            }
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //store menu to sub menu
    public function storenavbarsubmenu(Request $req)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_editor');

        if($getRole >= $min_level)
        {
            $validator = Validator::make(request()->all(), [
                'mainmenu_id' => 'required',
                'submenu_name' => 'required',
                'submenu_link' => 'required',
            ]);

            if($validator->passes())
            {
                if(!is_null($req))
                {
                  $menu = new Submenu;
                  $menu->mainmenu_id = $req->mainmenu_id;
                  $menu->submenu_name = $req->submenu_name;
                  $menu->submenu_link = $req->submenu_link;
                  $menu->save();

                  $req->session()->flash('success', 'Successfully added!');
                  return redirect()->action('NavbarController@addnavbar');
                }
                else
                {
                  $req->session()->flash('warning', 'Error, submenu is empty! Please select submenu.');
                  return redirect()->action('NavbarController@addnavbar');
                }
            }
            else
            {
              $req->session()->flash('warning', 'Error, submenu is empty! Please select submenu.');
              return redirect()->action('NavbarController@addnavbar');

            }
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //list main menu & sub menu
    public function listnavbar()
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_editor');

        if($getRole >= $min_level)
        {
            $allmainmenu = Mainmenu::all();
            $allsubmenu = Submenu::all();

            return view('navbar/listnavbar', ['role' => $getRole])->with(compact('allmainmenu', $allmainmenu, 'allsubmenu', $allsubmenu));
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //delete main menu navbar
    public function deletemainmenu($id)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_editor');

        if($getRole >= $min_level)
        {
            $checkmainmenu = Mainmenu::count();
            if($checkmainmenu == 1)
            {
                $submenu = Submenu::where('id', '>', '0');
                $submenu->delete();

                $mainmenu = Mainmenu::find($id);
                $mainmenu->delete();
                return redirect('admin/home/navbar');
            }
            else
            {
                $mainmenu = Mainmenu::find($id);
                $mainmenu->delete();
                return redirect('admin/home/navbar');
            }
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }

    }
    //delete sub menu navbar
    public function deletesubmenu($id)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_editor');

        if($getRole >= $min_level)
        {
            $submenu = Submenu::find($id);
            $submenu->delete();
            return redirect('admin/home/navbar');
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
}
