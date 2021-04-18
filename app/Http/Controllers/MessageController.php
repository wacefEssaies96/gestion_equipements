<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\User;
use App\Message;
use App\Notifications\ChatMessage;
use Notification;


class MessageController extends Controller
{
    public function get(){
        $users = User::where('id', '!=', Auth::id())->get();
        return response()->json($users);
    }

    public function getConversation($id){
        // get all messages between the authenticated user and the selected user
        $messages = Message::where(function($q) use ($id) {
            $q->where('from', auth()->id());
            $q->where('to', $id);
        })->orWhere(function($q) use ($id) {
            $q->where('from', $id);
            $q->where('to', auth()->id());
        })
        ->get();
        return response()->json($messages);
    }

    public function send(Request $request){
        $message = Message::create([
            'from' => Auth::id(),
            'to' => $request->contact_id,
            'text' => $request->text
        ]);
        $user = User::find($request->contact_id);
        $reciever = User::find($message->from);
        Notification::send($user, new ChatMessage($message,$reciever));
        return response()->json($message);
    }
}
