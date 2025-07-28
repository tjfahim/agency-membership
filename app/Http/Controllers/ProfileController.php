<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProfileController extends Controller
{
    //
    public function profile_setting()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile(['user_id'=>$user->id]);

        return Inertia::render('Profile/settings',[
            'user'=>$user,
            'profile'=>$profile,
        ]);

    }
       public function update(Request $request)
    {
        $request->validate([
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'email' => 'nullable|email|unique:profiles,email,' . Auth::id() . ',user_id',
            'dob' => 'nullable|date',
            'avatar'=>'nullable|mimes:jpg,jpeg,png|max:2048'
        ]);
   
         \Log::info('profile data',['data'=> $request->all()]);
        $data =  $request->only(['phone', 'address', 'dob','email']);    
       
        \Log::info('profile data',['data'=> $data]);
        try{
        $user = Auth::user();
        $profile = Auth::user()->profile;
        if($request->hasFile('avatar')){
            
            $name = $request->file('avatar')->hashName();
           
            if($profile && $profile->avatar){
                Storage::disk('public')->delete($profile->avatar);
            }
            $request->file('avatar')->storeAs('avatars/',$name,'public');
            $data['avatar'] = 'avatars/'.$name; 
        }
           \Log::info('profile data',['data'=> $data]);
          $user->profile()->updateOrCreate(
            ['user_id' => $user->id],
            $data 
        );
         return redirect()->route('profile.setting')->with('success', 'Profile updated.');
        }catch(\Exception $e){
            dd($e->getMessage());
              \Log::info('profile data',['error'=> $e->getMessage()]);
            return  redirect()->route('profile.setting')->with(['error'=>'Profile could not update']);
        }       
    }

    public function passwordUpdate(Request $request)
    {
        $request->validate([
            'current_password' =>"required",
            'password'=>['required','confirmed',Password::min(6)],
        ]);
        $user = Auth::user();
        if(!Hash::check($request->current_password,$user->password)){
             return  redirect()->route('profile.setting')->with(['error'=>'Wrong current Password']);
        }
        $user->password = Hash::make($request->password);
        $user->save();
        return  redirect()->route('profile.setting')->with(['success'=>'Password changed successfully']);
    }
}
