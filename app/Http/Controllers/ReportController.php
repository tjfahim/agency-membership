<?php

namespace App\Http\Controllers;

use App\Models\Payment;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        //
        
        $query = Payment::with(['subscription.package.softwares','subscription.user']);
       

        if($request->filled('username')){
            $query->whereHas('subscription.user',function($q) use ($request){
                $q->where('name','LIKE','%'.$request->input('username').'%');
            });
        }  
        if($request->filled('software')){
            $query->whereHas('subscription.package.softwares',function($q) use($request){
                $q->where('name','LIKE','%'. $request->input('software').'%');
            });
        }
        if($request->filled('package')){
            $query->whereHas('subscription.package',function($q)use($request){
                $q->where('name','LIKE','%'.$request->input('package').'%');
            });
        }
        if($request->filled('last_payment_date')){
            $query->whereDate('created_at',$request->input('last_payment_date'));
        }
        if($request->filled('expire_date')){
            $query->whereHas('subscription',function($q) use ($request){
                $q->whereDate('end_at',$request->input('expire_date'));
            });
        }
        if($request->filled('auto_renew')){
            $query->whereHas('subscription',function($q) use ($request){
                $q->where('auto_renew',$request->input('auto_renew'));
            });
        }
        if($request->filled('status')){
            $query->whereHas('subscription',function($q) use ($request){
                $q->where('payment_status',$request->input('status'));
            });
        }
        if($request->filled('price')){
            $query->where('amount',$request->input('price'));
        }
        $users = $query->paginate(20)->appends($request->all());
        $pagination = [
        'total' => $users->total(),
        'per_page' => $users->perPage(),
        'current_page' => $users->currentPage(),
        'last_page' => $users->lastPage(),
        'from' => $users->firstItem(),
        'to' => $users->lastItem(),
    ]; 
        return Inertia::render('Report/index', ['users' => $users, 'pagination' => $pagination]);

    }
}
