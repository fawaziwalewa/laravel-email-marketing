<?php

use App\Models\Status;
use App\Models\Subscriber;
use Illuminate\Http\Request;
use Vinkla\Hashids\Facades\Hashids;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Route;
use Filament\Notifications\Notification;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect()->route('filament.pages.dashboard');
});

Route::get('unsubscribe', function(){
    return view('unsubscribe');
});

Route::post('/unsubscribe', function(Request $request){
    // Decode strings.
    $id = $request->user_id;
    if(!empty($id)){
        $decode_id = Hashids::decode($request->user_id)[0];
        $subscriber = Subscriber::find($decode_id);
        if(!empty($subscriber)){
            Subscriber::where('id', $decode_id)->first()->update([
                'is_subscribed' => 0,
            ]);
            $notice = Notification::make()
                ->title('Unsubscribed successfully')
                ->success()
                ->body('You can still use **CARCANDY** coupon code anytime.')
                ->send();
            return view('unsubscribe', compact('notice'));
        }
    }

    $notice = Notification::make()
            ->title('Unable to unsubscribe')
            ->danger()
            ->body('Possible reason invalid user ID.')
            ->send();

    return view('unsubscribe', compact('notice'));
})->name('unsubscribe');

Route::get('/email-tracking/open', function(){
    $status = Status::where('name', 'open')->first();

    $status->update([
        'count' => $status->count + 1,
    ]);
})->name('emailOpen');

