<?php
declare(strict_types=1);

namespace App\Http\Controllers\API;

use App\Contracts\RoomRepositoryInterfaceContract;
use App\Http\Controllers\Controller;

class RoomController extends Controller
{
    protected $roomRepository;

    public function __construct(RoomRepositoryInterfaceContract $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function index()
	{
        return response()->json($this->roomRepository->getAll());
	}
}
