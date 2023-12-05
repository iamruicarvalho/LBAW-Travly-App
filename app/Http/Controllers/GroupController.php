<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;

class GroupController extends Controller
{
    // Exibe a página de criação de grupo
    public function showCreateForm()
    {
        return view('group.create');
    }

    // Cria um novo grupo
    public function create(Request $request)
    {

        $this->authorize('create', Group::class);
        
        $request->validate([
            'name_' => 'required|string|max:255',
            'description_' => 'nullable|string',
        ]);

        // Crie o grupo
        $group = new Group();
        $group->name_ = $request->input('name_');
        $group->description_ = $request->input('description_');
        $group->save();

        // Associe o usuário autenticado como proprietário do grupo
        $group->owners()->attach(auth()->user()->id);

        return redirect()->route('group.show', ['groupId' => $group->groupID])
            ->with('success', 'Group created successfully');
    }

    // Exibe a página de um grupo específico
    public function showGroup($groupid)
    {
        $group = Group::find($groupid);

        if (!$group) {
            return redirect()->route('home')->with('error', 'Group not found');
        }

        return view('partials.showGroup', compact('group'));
    }

    // Exibir detalhes de um grupo específico
    public function groupDetails($groupid)
    {
        $group = Group::find($groupid);

        if (!$group) {
            return redirect()->route('home')->with('error', 'Group not found');
        }
    
        return view('partials.groupDetails', compact('group'));
    }

    // Mostra a lista de grupos
    public function list()
    {
        $groups = Group::all();

        return view('pages.groups', compact('groups'));
    }

    // Remove a user from the group
    public function removeUser($groupid, $userId)
    {
        // Find the group
        $group = Group::find($groupid);

        // Check if the group exists
        if (!$group) {
            return redirect()->route('home')->with('error', 'Group not found');
        }

        // Check if the authenticated user is the owner of the group
        if (!$group->owners->contains(auth()->user())) {
            return redirect()->route('home')->with('error', 'Permission denied');
        }

        // Detach the user from the group
        $group->users()->detach($userId);

        return redirect()->route('group.details', ['groupid' => $groupid])
            ->with('success', 'User removed from the group successfully');
    }

    public function searchUsers(Request $request)
    {
        $term = $request->input('term');

        $users = User::where('username', 'like', '%' . $term . '%')->select(['id', 'username'])->get();

        return response()->json($users);
    }

    // Permite que um usuário solicite ingresso em um grupo
    public function requestJoin($groupId)
    {
        $user = auth()->user();
        $group = Group::find($groupId);

        if (!$group) {
            return redirect()->route('home')->with('error', 'Group not found');
        }

        // Adicione a solicitação ao grupo
        $group->joinRequests()->attach($user->id);

        return redirect()->route('group.show', ['groupId' => $groupId])
            ->with('success', 'Join request sent successfully');
    }

    // Permite que um proprietário do grupo aceite uma solicitação de entrada
    public function acceptJoinRequest($groupId, $id)
    {
        $group = Group::find($groupId);

        if (!$group) {
            return redirect()->route('home')->with('error', 'Group not found');
        }

        // Verifique se o usuário autenticado é o proprietário do grupo
        if (!$group->isOwner(auth()->user()->id)) {
            return redirect()->route('home')->with('error', 'Permission denied');
        }

        // Aceite a solicitação de entrada
        $group->joinRequests()->detach($id);
        $group->users()->attach($id);

        return redirect()->route('group.show', ['groupId' => $groupId])
            ->with('success', 'User joined the group successfully');
    }
}
