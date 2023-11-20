<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Message;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class MessageController extends Controller
{
    public function sendMessage(Request $request)
    {
        $this->validate($request, [
            'description' => 'required',
            'receiver' => 'required|exists:user_,userID', 
        ]);

        $receiverId = $request->input('receiver');
        $senderId = auth()->user()->userID;

        if ($senderId == $receiverId) {
            return response()->json(['error' => 'Você não pode enviar mensagens para você mesmo.'], 400);
        }

        // Criar a mensagem
        $message = new Message();
        $message->description_ = $request->input('description');
        $message->sender = $senderId;
        $message->receiver = $receiverId;
        $message->time_ = now();
        $message->save();

        return response()->json(['message' => 'Mensagem enviada com sucesso.']);
    }

    public function getMessages(Request $request, $userId)
    {
        
        if (!User::where('userID', $userId)->exists()) {
            return response()->json(['error' => 'Usuário não encontrado.'], 404);
        }

        // mensagens entre o usuário autenticado e o usuário especificado
        $messages = Message::where(function ($query) use ($userId) {
            $query->where('sender', auth()->user()->userID)->where('receiver', $userId);
        })->orWhere(function ($query) use ($userId) {
            $query->where('sender', $userId)->where('receiver', auth()->user()->userID);
        })->orderBy('time_', 'asc')->get();

        return response()->json(['messages' => $messages]);
    }

    public function getConversations(Request $request)
    {
        // Recuperar todas as conversas do usuário
        $conversations = DB::select(
            'SELECT DISTINCT ON (u.userID) u.userID, u.username, m.description_, m.time_
            FROM user_ u
            LEFT JOIN message_ m ON u.userID = m.sender OR u.userID = m.receiver
            WHERE u.userID != ?
            ORDER BY u.userID, m.time_ DESC',
            [auth()->user()->userID]
        );

        return response()->json(['conversations' => $conversations]);
    }

}
