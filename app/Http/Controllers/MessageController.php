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
            'receiver' => 'required|exists:user_,id', 
        ]);

        $receiverId = $request->input('receiver');
        $senderId = auth()->user()->id;

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

    public function getMessages(Request $request, $id)
    {
        
        if (!User::where('id', $id)->exists()) {
            return response()->json(['error' => 'Usuário não encontrado.'], 404);
        }

        // mensagens entre o usuário autenticado e o usuário especificado
        $messages = Message::where(function ($query) use ($id) {
            $query->where('sender', auth()->user()->id)->where('receiver', $id);
        })->orWhere(function ($query) use ($id) {
            $query->where('sender', $id)->where('receiver', auth()->user()->id);
        })->orderBy('time_', 'asc')->get();

        return response()->json(['messages' => $messages]);
    }

    public function getConversations(Request $request)
    {
        // Recuperar todas as conversas do usuário
        $conversations = DB::select(
            'SELECT DISTINCT ON (u.id) u.id, u.username, m.description_, m.time_
            FROM user_ u
            LEFT JOIN message_ m ON u.id = m.sender OR u.id = m.receiver
            WHERE u.id != ?
            ORDER BY u.id, m.time_ DESC',
            [auth()->user()->id]
        );

        return response()->json(['conversations' => $conversations]);
    }

}
