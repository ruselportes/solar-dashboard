<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\SystemEvent;

class SystemEventController extends Controller
{
    public function recent(Request $request)
    {
        $limit = min((int) ($request->query('limit', 20)), 100);

        $events = SystemEvent::orderBy('timestamp', 'desc')
            ->limit($limit)
            ->get();

        return response()->json($events);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        if (!isset($data['timestamp'])) {
            $data['timestamp'] = now();
        }
        // Remove source if present as it's not in the schema
        unset($data['source']);
        
        $event = SystemEvent::create($data);
        return response()->json($event, 201);
    }
}
