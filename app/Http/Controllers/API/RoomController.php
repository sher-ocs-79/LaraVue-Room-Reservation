<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Room;

class RoomController extends Controller
{
	public function index()
	{
		$books = Room::all()->toArray();
		return array_reverse($books);
	}

	public function add(Request $request)
	{
		$book = new Room([
			'name' => $request->name
		]);
		$book->save();

		return response()->json('The room successfully added');
	}

	public function edit($id)
	{
		$book = Room::find($id);
		return response()->json($book);
	}

	public function update($id, Request $request)
	{
		$book = Room::find($id);
		$book->update($request->all());

		return response()->json('The room successfully updated');
	}

	public function delete($id)
	{
		$book = Room::find($id);
		$book->delete();

		return response()->json('The room successfully deleted');
	}
}
