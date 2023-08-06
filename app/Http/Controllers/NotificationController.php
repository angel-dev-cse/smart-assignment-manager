<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{

    public function index()
    {
        //
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)->orderBy('created_at', 'desc')->get();
        $unreadNotificationsCount = $notifications->where('read', false)->count();

        return view('your_notifications_view', compact('notifications', 'unreadNotificationsCount'));
    }

    public function getNewNotifications()
    {
        $user = Auth::user();
        $notifications = Notification::where('user_id', $user->id)->orderBy('created_at', 'desc')->where('read', false)->get();
        $unreadNotificationsCount = $notifications->where('read', false)->count();

        return view('your_notifications_view', compact('notifications', 'unreadNotificationsCount'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'title' => 'required',
            'description' => 'required',
            'route' => 'required',
            'route_id' => 'integer'
        ]);

        // Create the notification
        Notification::create([
            'user_id' => $request->user_id,
            'title' => $request->title,
            'description' => $request->description,
            'route' => $request->route,
            'route_id' => $request->route_id
        ]);
    }

    public function show(Notification $notification)
    {
        $notification->update(['seen' => true]);

        if (is_null($notification->route_id)) {
            return redirect(route($notification->route));
        } else {
            return redirect(route($notification->route, ['id' => $notification->route_id]));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function edit(Notification $notification)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Notification $notification)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Notification  $notification
     * @return \Illuminate\Http\Response
     */
    public function destroy(Notification $notification)
    {
        //
    }
}