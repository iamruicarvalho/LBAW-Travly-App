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


    public function showAllConversations()
    {
        $conversations = Message::select('receiver', 'sender')
            ->distinct()
            ->where('sender', auth()->id())
            ->orWhere('receiver', auth()->id())
            ->get();
    
        $users = User::whereIn('id', $conversations->pluck('receiver')->merge($conversations->pluck('sender'))->unique())
            ->get(['id', 'name_', 'username']); // Adicione 'username' aqui para buscar o campo username
    
        foreach ($conversations as $conversation) {
            if ($conversation->receiver) {
                $user = $users->where('id', $conversation->receiver)->first();
                $conversation->receiverName = $user ? $user->name_ : 'Usuário Desconhecido';
                $conversation->receiverUsername = $user ? $user->username : 'Username Desconhecido';
            }
        }
    
        return view('messages.show', ['conversations' => $conversations, 'users' => $users]);
    }
    
    

    public function show($id)
    {
        // Lógica para mostrar mensagens específicas
        $messages = Message::where('id', $id)
            ->where(function ($query) {
                $query->where('sender', auth()->id())
                    ->orWhere('receiver', auth()->id());
            })
            ->get();

        return view('messages.show-conversation', ['messages' => $messages]);
    }

    public function showMessages($receiver)
    {
        // Obtenha o usuário autenticado
        $user = Auth::user();

        // Verifique se o usuário autenticado tem uma conversa com o receptor
        $conversation = $user->conversations()->where('receiver', $receiver)->first();

        // Se não houver uma conversa existente, crie uma nova
        if (!$conversation) {
            $conversation = $user->conversations()->create([
                'receiver' => $receiver,
            ]);
        }

        // Obtenha as mensagens da conversa
        $messages = Message::where('conversation_id', $conversation->id)->get();

        // Obtenha todas as conversas do usuário autenticado
        $conversations = $user->conversations;

        // Retorne a view com as mensagens da conversa selecionada e todas as conversas
        return view('mensagens.show', [
            'conversations' => $conversations,
            'selectedConversation' => $conversation,
            'messages' => $messages,
        ]);
    }

}
