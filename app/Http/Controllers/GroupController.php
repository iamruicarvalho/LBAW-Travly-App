<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\User;
use App\Models\Owner;
use App\Models\Belongs;

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
        $selectedMembers = json_decode($request->input('selectedMembers'));
        
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

        if ($selectedMembers) {
            foreach ($selectedMembers as $userId) {
                if ($userId !== auth()->user()->id && !$group->users()->find($userId)) {
                    $group->users()->attach($userId);
                }
            }
        }

        $updatedUsers = $group->users;

        return redirect()->route('groups.showGroups')->with(['groupid' => $group->groupid, 'users' => $updatedUsers]);
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

    // Delete group
    public function deleteGroup($groupid)
    {
        $group = Group::find($groupid);
    
        if (!$group) {
            return redirect()->route('groups.showGroups')->with('error', 'Group not found');
        }
        
        $group->posts()->delete();
        $group->messages()->delete();
        Belongs::where('groupid', $groupid)->delete();
        Owner::where('groupid', $groupid)->delete();
    
        $group->delete();
    
        return redirect()->route('groups.showGroups')->with('success', 'Group deleted successfully');
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

    public function addUser($groupid, $userid)
    {
        $group = Group::find($groupid);

        if (!$group) {
            return response()->json(['error' => 'Group not found'], 404);
        }

        if (!$group->owners->contains(auth()->user())) {
            return response()->json(['error' => 'Permission denied'], 403);
        }

        $group->users()->attach($userid);

        $user = User::find($userid);

        $response = [
            'user' => $user,
            'users' => $group->users,
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

    public function leaveGroup($groupid)
    {
        $group = Group::find($groupid);

        if (!$group) {
            return redirect()->route('groups.showGroups')->with('error', 'Group not found');
        }

        $user = auth()->user();

        $user->groups()->detach($groupid);

        return redirect()->route('groups.showGroups')->with('success', 'Left the group successfully.');
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
