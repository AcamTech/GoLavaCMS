<?php

namespace App\Http\Controllers;
use App\Blog;
use App\Page;
use App\User;
use App\Setting;
use App\Media;
use App\Roles\Role;
use File;
use Illuminate\Validation\Rule;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;

use Illuminate\Http\Request;

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
    //----------------------------------------------------------------------Dashboard
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        return view('home', ['role' => $getRole]);
    }
    //----------------------------------------------------------------------Dashboard

    //----------------------------------------------------------------------Blog
    //add blog
    public function showadd()
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_contributor');

        if($getRole >= $min_level)
        {
          $media = Media::all();//add media upload
          return view('blog/addblog', ['role' => $getRole])->with(compact('media'));
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }

    }
    //save blog after add blog
    public function storeadd(Request $request)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_contributor');

        if($getRole >= $min_level)
        {
            $validator = Validator::make(request()->all(), [
                'title' => 'required|unique:blogs'
            ]);

            if($validator->passes())
            {
                $blog = new Blog;
                $blog->title   = $request->title;
                $blog->content = $request->content;
                $blog->author = $request->author;
                $blog->slug = Str::slug($request->title);
                $blog->titleseo = $request->titleseo;
                $blog->descseo = $request->descriptionseo;
                $blog->keywordseo = $request->keywordseo;
                $save = $blog->save();

                if($save){
                    $request->session()->flash('success', 'Successfully saved!');
                    return redirect()->action('HomeController@blog');
                }
            }
            else
            {
                return Redirect::to('admin/home/blog/add')
                  ->withErrors($validator)
                  ->withInput();
            }
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //list blog
    public function blog(Request $request)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_contributor');

        if($getRole >= $min_level)
        {
            $search = $request->search;
            if(!empty($request->number))
            {
              $number = $request->number;
            }
            else
            {
              $number = 10;
            }

            if(!empty($search))
            {
              $allblog = Blog::where('title', 'LIKE', '%'.$search.'%')->paginate($number);
            }
            else
            {
              $allblog = Blog::paginate($number);
            }

            return view('blog/listblog', ['role' => $getRole])->with(compact('allblog', $allblog));
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //edit blog
    public function showedit($id)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_contributor');

        if($getRole >= $min_level)
        {
            $media = Media::all();//add medai upload
            $blog = Blog::find($id);

            if(!$blog)
            abort(404);
            return view('blog/editblog', ['blog' => $blog, 'media' => $media, 'role' => $getRole]);
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //save blog after edit blog
    public function storeedit(Request $request, $id)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_contributor');

        if($getRole >= $min_level)
        {
            $blog = Blog::find($id);
            $validator = Validator::make(request()->all(), [
                'title' => ['required', Rule::unique('blogs')->ignore($blog->id),],
                'slug' => ['required', Rule::unique('blogs')->ignore($blog->id),],
            ]);

            if($validator->passes())
            {

                $blog->title   = $request->title;
                $blog->content = $request->content;
                $blog->author = $request->author;
                $blog->slug = Str::slug($request->slug);
                $blog->titleseo = $request->titleseo;
                $blog->descseo = $request->descriptionseo;
                $blog->keywordseo = $request->keywordseo;
                $save = $blog->save();

                if($save)
                {
                  $request->session()->flash('success', 'Successfully saved!');
                  return redirect()->action('HomeController@blog');
                }
            }
            else
            {
                return Redirect::to('admin/home/blog/edit'.$id)
                  ->withErrors($validator)
                  ->withInput();
            }
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //delete blog
    public function deleteblog(Request $request, $id)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_contributor');

        if($getRole >= $min_level)
        {
            $blog = Blog::find($id);
            $delete = $blog->delete();

            if($delete)
            {
              $request->session()->flash('success', 'Successfully deleted the blog!');
              return redirect()->action('HomeController@blog');
            }
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //----------------------------------------------------------------------Blog

    //----------------------------------------------------------------------Profile
    //view individual profile from ID
    public function profileid($id)
    {
        $userRole    = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $userRole->role;
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_admin');

        if($getRole >= $min_level)
        {

            $user = User::where('id', '=', $id)->first();
            $email = $user->email;
            return view('profile/viewprofile', ['user' => $user, 'email' => $email, 'role' => $getRole]);
        }
        else
        {
            return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //view individual profile from session
    public function profile()
    {
        $user = User::where('email', '=', Auth::user()->email)->first();
        $email = Auth::user()->email;
        $getRole = $user->role;
        return view('profile/viewprofile', ['user' => $user, 'email' => $email, 'role' => $getRole]);
    }
    //profile edit
    public function profileedit()
    {
        $user = User::where('email', '=', Auth::user()->email)->first();
        $getRole = $user->role;
        return view('profile/editprofile', ['user' => $user, 'role' => $getRole, "id" => "profile"]);
    }
    //edit all user profile
    public function profileeditid($id)
    {

        $userRole = User::where('email', '=', Auth::user()->email)->first();
        $getRole = $userRole->role;
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_admin');

        if($getRole >= $min_level)
        {
            $user = User::find($id);
            return view('profile/editprofile', ['user' => $user, 'role' => $getRole, "id" => "id"]);
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //save after edit profile
    public function profileeditstore(Request $request, $id)
    {
        $validator = Validator::make(request()->all(), [
            'name' => 'string|max:255',
            'email' => 'string|email|max:255',
            'role' => 'string',
            'password' => 'string|min:6|confirmed',
        ]);

        $user = User::find($id);
        if($validator->passes())
        {
            $user->name     = $request->name;
            $user->email    = $request->email;
            $user->role    = $request->role;
            if (!empty($request->password))
            {
              $user->password = Hash::make($request->password);
            }
            $save = $user->save();
            if($save)
            {
              $request->session()->flash('success', 'Successfully saved!');
              return redirect()->action('HomeController@profileid', $id);
            }
        }
        else
        {
            return Redirect::to('admin/home/profile/edit')
              ->withErrors($validator)
              ->withInput();
        }
    }
    //list user
    public function user()
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_admin');

        if($getRole >= $min_level)
        {
            $allUser = User::all();
            return view('profile/listuser', ['role' => $getRole])->with(compact('allUser', $allUser));
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //delete user
    public function deleteuser($id)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_admin');

        if($getRole >= $min_level)
        {
            $user = User::find($id);
            $delete = $user->delete();

            if($delete)
            {
              $request->session()->flash('success', 'Successfully deleted!');
              return redirect()->action('HomeController@user');
            }
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //approved user
    public function approveduser(Request $request, $id)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_admin');

        if($getRole >= $min_level)
        {
            $user = User::find($id);
            $user->approved = $request->approved;
            $save = $user->save();

            if($save){
                $request->session()->flash('success', 'Successfully!');
                return redirect()->action('HomeController@user');
            }
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //----------------------------------------------------------------------Profile

    //----------------------------------------------------------------------Page
    //list page
    public function page(Request $request)
    {
      $role        = User::where('email', '=', Auth::user()->email)->first();
      $getRole     = $role->role; //get curren level user
      $getRoleMin  = new Role('Role');
      $min_level   = $getRoleMin->getRole('is_contributor');

      if($getRole >= $min_level)
      {
          if(!empty($request->number))
          {
            $number = $request->number;
          }
          else
          {
            $number = 10;
          }

          $search = $request->search;
          $allsetting = Setting::all();
          if($allsetting->isNotEmpty())
          {
              if(!empty($search))
              {
                $allsettings = Setting::find(1);
                $allpage = Page::where('title', 'LIKE', '%'.$search.'%')->paginate($number);
                return view('page/page', ['allsettings' => $allsettings, 'role' => $getRole])->with(compact('allpage', $allpage));
              }
              else
              {
                $allsettings = Setting::find(1);
                $allpage = Page::paginate($number);
                return view('page/page', ['allsettings' => $allsettings, 'role' => $getRole])->with(compact('allpage', $allpage));
              }
          }
          else
          {
              if(!empty($search))
              {
                $allpage = Page::where('title', 'LIKE', '%'.$search.'%')->paginate($number);
                return view('page/page', ['role' => $getRole])->with(compact('allpage', $allpage));
              }
              else
              {
                $allpage = Page::paginate($number);
                return view('page/page', ['role' => $getRole])->with(compact('allpage', $allpage));
              }
          }
      }
      else
      {
        return view('role/dontaccess', ['role' => $getRole]);
      }
    }
    //add page
    public function addpage()
    {
      $role        = User::where('email', '=', Auth::user()->email)->first();
      $getRole     = $role->role; //get curren level user
      $getRoleMin  = new Role('Role');
      $min_level   = $getRoleMin->getRole('is_contributor');

      if($getRole >= $min_level)
      {
          $media = Media::all();//add medai upload
          return view('page/addpage', ['role' => $getRole])->with(compact('media'));
      }
      else
      {
        return view('role/dontaccess', ['role' => $getRole]);
      }
    }
    //save page after add page
    public function storepage(Request $request)
    {
      $role        = User::where('email', '=', Auth::user()->email)->first();
      $getRole     = $role->role; //get curren level user
      $getRoleMin  = new Role('Role');
      $min_level   = $getRoleMin->getRole('is_contributor');

      if($getRole >= $min_level)
      {
          $validator = Validator::make(request()->all(), [
              'title' => 'required|unique:pages'
          ]);
          if($validator->passes())
          {
              $page = new Page;
              $page->title   = $request->title;
              $page->content = $request->content;
              $page->author = $request->author;
              $page->slug = Str::slug($request->title);
              $page->titleseo = $request->titleseo;
              $page->descseo = $request->descriptionseo;
              $page->keywordseo = $request->keywordseo;
              $save = $page->save();

              if($save)
              {
                $request->session()->flash('success', 'Successfully saved!');
                return redirect()->action('HomeController@page');
              }
          }
          else
          {
              return Redirect::to('admin/home/page/add')
                ->withErrors($validator)
                ->withInput();
          }
      }
      else
      {
        return view('role/dontaccess', ['role' => $getRole]);
      }

    }
    //edit page
    public function showeditpage($id)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_contributor');

        if($getRole >= $min_level)
        {
            $page = Page::find($id);
            $media = Media::all();//add medai upload

            if(!$page)
            abort(404);

            return view('page/editpage', ['page' => $page, 'media' => $media, 'role' => $getRole]);
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //save page after edit page
    public function storeeditpage(Request $request, $id)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_contributor');

        if($getRole >= $min_level)
        {
            $page = Page::find($id);
            $validator = Validator::make(request()->all(), [
                'title' => ['required', Rule::unique('pages')->ignore($page->id),],
                'slug' => ['required', Rule::unique('pages')->ignore($page->id),],
            ]);

            if($validator->passes())
            {
                  $page->title   = $request->title;
                  $page->content = $request->content;
                  $page->author = $request->author;
                  $page->slug = Str::slug($request->slug);
                  $page->titleseo = $request->titleseo;
                  $page->descseo = $request->descriptionseo;
                  $page->keywordseo = $request->keywordseo;
                  $save = $page->save();

                  if($save)
                  {
                    $request->session()->flash('success', 'Successfully saved!');
                    return redirect()->action('HomeController@page');
                  }
              }
              else
              {
                  return Redirect::to('admin/home/page/edit/'.$id)
                    ->withErrors($validator)
                    ->withInput();
              }
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //delete page
    public function deletepage(Request $request, $id)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_contributor');

        if($getRole >= $min_level)
        {
            $page = Page::find($id);
            $delete = $page->delete();

            if($delete)
            {
              $request->session()->flash('success', 'Successfully deleted the page!');
              return redirect()->action('HomeController@page');
            }
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //----------------------------------------------------------------------Page

    //----------------------------------------------------------------------Setting
    //setting
    public function setting()
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_admin');

        if($getRole >= $min_level)
        {
            $getpage    = Page::all(['title']);
            $allsetting = Setting::all();
            if(!empty($allsetting))
            {
                $setting = Setting::find(1);
                return view('setting/setting', ['setting' => $setting, 'role' => $getRole])->with(compact('getpage', $getpage, 'allsetting', $allsetting));
            }
            else {
              return view('setting/setting', ['role' => $getRole])->with(compact('getpage', $getpage, 'settings', $allsetting));
            }
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //save setting
    public function storesetting(Request $req, $id)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_admin');

        if($getRole >= $min_level)
        {
              $setting = Setting::all();
              if($setting->isNotEmpty())
              {
                  $validator = Validator::make(request()->all(), [
                      'theme' => 'required',
                  ]);

                  if($validator->passes())
                  {

                    $setting = Setting::find($id);
                    $setting->home_page = $req->page;
                    $setting->title_site = $req->sitetitle;
                    $setting->theme = $req->theme;
                    $setting->favicon = $req->favicon;
                    $setting->author = $req->author;
                    $setting->googlewebmaster = $req->googlewebmaster;
                    $setting->bingwebmaster = $req->bingwebmaster;
                    $setting->alexa = $req->alexa;
                    $setting->googleanalytic = $req->googleanalytic;
                    $setting->revistafter = $req->revistafter;
                    $setting->robots = $req->robots;
                    $save = $setting->save();

                    if($save)
                    {
                      $req->session()->flash('success', 'Successfully saved!');
                      return redirect()->action('HomeController@setting');
                    }
                  }
                  else
                  {
                    $req->session()->flash('warning', 'Theme can\'t empty!');
                    return redirect()->action('HomeController@setting');
                  }
              }
              else
              {
                  $validator = Validator::make(request()->all(), [
                      'theme' => 'required',
                  ]);

                  if($validator->passes())
                  {
                    $setting = New Setting;
                    $setting->home_page = $req->page;
                    $setting->title_site = $req->sitetitle;
                    $setting->theme = $req->theme;
                    $setting->favicon = $req->favicon;
                    $setting->author = $req->author;
                    $setting->googlewebmaster = $req->googlewebmaster;
                    $setting->bingwebmaster = $req->bingwebmaster;
                    $setting->alexa = $req->alexa;
                    $setting->googleanalytic = $req->googleanalytic;
                    $setting->revistafter = $req->revistafter;
                    $setting->robots = $req->robots;
                    $save = $setting->save();

                    if($save)
                    {
                      $req->session()->flash('success', 'Successfully saved!');
                      return redirect()->action('HomeController@setting');
                    }
                }
                else
                {
                  $req->session()->flash('warning', 'Theme can\'t empty!');
                  return redirect()->action('HomeController@setting');
                }
            }
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //----------------------------------------------------------------------Setting

    //----------------------------------------------------------------------Media
    //media
    public function media(Request $request)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_contributor');

        if($getRole >= $min_level)
        {
            $search = $request->search;
            if(!empty($search))
            {
              $media = Media::where('image_name', 'LIKE', '%'.$search.'%')->get();
            }
            else {
              $media = Media::all();
            }
            return view('media/media', ['role' => $getRole])->with(compact('media'));
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //add media
    public function addmedia()
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_contributor');

        if($getRole >= $min_level)
        {
            return view('media/addmedia', ['role' => $getRole]);
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //upload & save image
    public function uploadimage(Request $request)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_contributor');

        if($getRole >= $min_level)
        {
            if($request->hasFile('media')) { //check if isset media
              $destinationPath = base_path().'/public/img';//chmod 0777
              $files = $request->file('media');

              foreach($files as $file){
                $filename = $file->getClientOriginalName();
                $extension = $file->getClientOriginalExtension();
                $reName = str_replace(" ","-",$filename);

                //check if exists
                if (File::exists($destinationPath."/".$reName)) {
                  $request->session()->flash('warning', 'Your file is already uploaded!');
                  return redirect()->action('HomeController@media');
                }
                else {
                  $medias = $reName;
                  $images[] = $medias;
                  $author[] = $request->author;
                  $exten[] = $extension;

                  $media = new Media;
                  for($i = 0; $i < count($images); $i++) {
                    $media->image_name = $images[$i];
                    $media->path       = url('/')."/public/img/".$images[$i];
                    $media->author     = $author[$i];
                    $media->extension  = $exten[$i];
                    $media->save();
                  }
                  $file->move($destinationPath, $medias);//save to path
                }
              }
              $request->session()->flash('success', 'Successfully uploaded your image!');
              return redirect()->action('HomeController@media');
            }
            else
            {
              $request->session()->flash('warning', 'Please select a file.');
              return redirect()->action('HomeController@addmedia');
            }
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
    //delete Media
    public function deletemedia(Request $request, $id)
    {
        $role        = User::where('email', '=', Auth::user()->email)->first();
        $getRole     = $role->role; //get curren level user
        $getRoleMin  = new Role('Role');
        $min_level   = $getRoleMin->getRole('is_contributor');

        if($getRole >= $min_level)
        {
            $media = Media::find($id);
            $name = $media->image_name;
            $destinationPath = base_path().'/public/img/';

            if(file_exists($destinationPath.$name)){
              @unlink($destinationPath.$name);
            }
            $delete = $media->delete();

            if($delete)
            {
              $request->session()->flash('success', 'Successfully deleted!');
              return redirect()->action('HomeController@media');
            }
        }
        else
        {
          return view('role/dontaccess', ['role' => $getRole]);
        }
    }
}
