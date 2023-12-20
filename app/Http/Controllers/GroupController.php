<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;

class GroupController extends Controller
{   
    public function showGroups()
    {
     $userGroups = auth()->user()->groups;

        return view('pages.groups', ['groups' => $userGroups]);
    }

    // Exibe a página de criação de grupo
    public function showCreateForm()
    {
        return view('partials.groupCreate');
    }

    // Cria um novo grupo
    public function createGroup(Request $request)
    {
        $request->validate([
            'name_' => 'required|string|max:255',
            'description_' => 'nullable|string',
        ]);

        $group = new Group();
        $group->name_ = $request->input('name_');
        $group->description_ = $request->input('description_');
        $group->save();

        $group->users()->attach(auth()->user()->id);
        $group->owners()->attach(auth()->user()->id);

        return response()->json(['groupId' => $group->groupid, 'users' => $group->users]);
    }

    // Edit group name
    public function editName(Request $request, $groupid)
    {
        $group = Group::find($groupid);

        if (!$group) {
            return redirect()->route('home')->with('error', 'Group not found');
        }

        $request->validate([
            'name_' => 'required|string|max:255',
        ]);

        $group->name_ = $request->input('name_');
        $group->save();

        return redirect()->route('group.details', ['groupid' => $groupid])
            ->with('success', 'Group name updated successfully');
    }

    // Edit group description
    public function editDescription(Request $request, $groupid)
    {
        $group = Group::find($groupid);

        if (!$group) {
            return redirect()->route('home')->with('error', 'Group not found');
        }

        $request->validate([
            'description_' => 'nullable|string',
        ]);

        $group->description_ = $request->input('description_');
        $group->save();

        return redirect()->route('group.details', ['groupid' => $groupid])
            ->with('success', 'Group description updated successfully');
    }

    // Delete group
    public function deleteGroup($groupid)
    {
        $group = Group::find($groupid);

        if (!$group) {
            return redirect()->route('groups')->with('error', 'Group not found');
        }

        //$this->authorize('delete', $group);

        $group->delete();

        return redirect()->route('groups')->with('success', 'Group deleted successfully');
    }

    // Exibe a página de um grupo específico
    public function showGroup($groupid)
    {
        $group = Group::find($groupid);

        if (!$group) {
            return redirect()->route('home')->with('error', 'Group not found');
        }

        $data = $group->posts; 

        return view('partials.showGroup', compact('group', 'data'));
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

    public function addUser(Request $request, $groupid, $userId)
    {
        // Find the group
        $group = Group::find($groupid);

        // Check if the group exists
        if (!$group) {
            return response()->json(['error' => 'Group not found'], 404);
        }

        // Check if the authenticated user is the owner of the group
        if (!$group->owners->contains(auth()->user())) {
            return response()->json(['error' => 'Permission denied'], 403);
        }

        // Add user to the group
        $group->users()->attach($userId);

        // Retrieve the user information if needed
        $user = User::find($userId);

        // You can customize the data you send in the response
        $response = [
            'user' => $user,
            'message' => 'User added to the group successfully',
        ];

        return response()->json($response);
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

    public function leaveGroup(Request $request)
    {
        $groupid = $request->input('groupid');

        $group = Group::find($groupid);

        if (!$group) {
            return redirect()->route('home')->with('error', 'Group not found');
        }

        $user = auth()->user();

        // Detach the user from the group
        $user->groups()->detach($groupid);

        return response()->json(['message' => 'Left the group successfully.']);
    }

    public function searchGroups(Request $request)
    {
        $query = $request->input('query');
    
        $groups = Group::where('name_', 'like', '%' . $query . '%')
            ->orWhere('description_', 'like', '%' . $query . '%')
            ->get();
    
        return response()->json($groups);
    }
}
