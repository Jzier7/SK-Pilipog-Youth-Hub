<?php

namespace App\Repositories;

use App\Classes\JsonResponseFormat;
use App\Models\Event;
use Illuminate\Support\Facades\DB;

class EventRepository extends JsonResponseFormat
{

    /**
     * Get paginated events
     *
     * @param array $params
     * @return array
     */
    public function retrievePaginate(array $params): array
    {
        $query = Event::query()->with(['games', 'category']);

        if (!empty($params['search'])) {
            $searchTerm = '%' . $params['search'] . '%';
            $query->where('name', 'like', $searchTerm);
        }

        if (!empty($params['category'])) {
            $query->where('category_id', $params['category']);
        }

        $currentPage = $params['currentPage'] ?? 1;
        $pageSize = $params['pageSize'] ?? 10;

        $events = $query->paginate($pageSize, ['*'], 'page', $currentPage);

        $retrievedCount = count($events->items());

        return [
            'message' => "{$retrievedCount} events retrieved successfully",
            'body' => $events->items(),
            'current_page' => $events->currentPage(),
            'from' => $events->firstItem(),
            'to' => $events->lastItem(),
            'last_page' => $events->lastPage(),
            'skip' => ($currentPage - 1) * $pageSize,
            'take' => $pageSize,
            'total' => $events->total(),
        ];
    }

    /**
     * Get all events
     *
     * @param array $params
     * @return array
     */
    public function retrieveAll(): array
    {
        $events = Event::with('category')->get()->map(function ($event) {
            return [
                'id' => $event->id,
                'name' => $event->name_with_category,
            ];
        });

        return [
            'message' => 'All events retrieved successfully',
            'body' => $events,
            'total' => $events->count(),
        ];
    }

    /**
     * Add an event.
     *
     * @param array $data
     * @return array
     */
    public function store(array $data): array
    {
        DB::beginTransaction();
        try {

            $event = Event::create([
                'name' => $data['name'],
                'category_id' => $data['category_id'],
            ]);

            DB::commit();
            return [
                'message' => 'Event added successfully',
                'body' => $event
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Update an event.
     *
     * @param array $data
     * @return array
     */
    public function update(array $data): array
    {
        DB::beginTransaction();
        try {
            $event = Event::findOrFail($data['id']);

            $event->update([
                'name' => $data['name'],
                'category_id' => $data['category_id'],
            ]);

            DB::commit();
            return [
                'message' => 'Event updated successfully',
                'body' => $event,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }

    /**
     * Delete an event.
     *
     * @param array $data
     * @return array
     */
    public function delete(array $data): array
    {
        DB::beginTransaction();
        try {
            $event = Event::findOrFail($data['id']);

            $event->delete();

            DB::commit();
            return [
                'message' => 'Event deleted successfully',
                'body' => $event,
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            return [
                'error' => $e->getMessage(),
                'status' => 500,
            ];
        }
    }
}

