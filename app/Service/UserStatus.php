<?php

namespace App\Service;

use App\Models\User;
use Illuminate\Support\Facades\Http;

class UserStatus
{
    /**
     * Create a new class instance.
     */
    private $user;
    public function __construct(User $user)
    {
        //
        $this->user = $user;
    }

    public function activeStatus()
    {
        if (!empty($this->user->web_url)) {
           try{
            $response = Http::withHeaders([
                'TECHLAB_API_TOKEN' => env('TECHLAB_API_TOKEN'),
            ])->put($this->user->web_url . "/api/user/change-status/" . $this->user->email, [
                'status' => true,
            ]);
            \Log::info("Status Changed",["user status is activated. email: "=>$this->user->email,"response: "=>$response->json()]);
           }catch(\Exception $e){
            \Log::error("Failed to change status for {$this->user->name} : ",$e->getMessage());
            return to_route('payment.index')->with(['error' => 'Something went wrong!' . $e->getMessage()]);
           }
        }
        \Log::info("User :" . $this->user);
    }
}
