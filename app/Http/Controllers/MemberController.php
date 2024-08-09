<?php

namespace App\Http\Controllers;
use App\Models\Member;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class MemberController extends Controller
{
    public function createMember(Request $req){
        try{
           $mem= Member::create([
                "name"=>$req->name,
                "email"=>$req->email,
                "contact_number"=>$req->contact_number,
                "password"=>$req->password,
                "address"=>$req->address,
            ]);
            return response()->json(['member_created' => true,"id"=>$mem->id]);
        }catch(\Exception $e){
            return response()->json(['member_created'=>false,'exception'=>$e->getMessage()],500);
        }
  
    }
    public function changePasswordAdmin(Request $req){
        $mem=Member::where('id',$req->id)->first();
        if($mem){
            $email=$mem->email;
            $login=Auth::guard("member")->attempt(['email'=>$email,'password'=>$req->oldPassword]);
            if($login){
                $mem->update(["password"=>$req->newPassword]);
                return response()->json(['changed_password'=>true]);
            }else{
                return response()->json(['changed_password'=>false,'message'=>"Old password didn't match"]);
            }
        }else{
            return response()->json(['changed_password'=>false,'message'=>"User not found"]);

        }
    }
    public function loginMember(Request $req){
        
        $login=Auth::guard("member")->attempt(['email'=>$req->email,'password'=>$req->password]);
        return response()->json([
            "login"=>$login
        ]);
    }
}
