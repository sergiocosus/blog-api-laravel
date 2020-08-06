<?php


namespace App\Http\Controllers\Api\V1;


use App\Core\Event\Event;
use App\Core\Event\EventService;
use App\Http\Controllers\Controller;
use App\Http\Requests\Event\CreateEventRequest;
use App\Http\Requests\Event\DeleteEventRequest;
use App\Http\Requests\Event\UpdateEventRequest;
use Illuminate\Http\Request;

class EventController extends Controller {
    /**
     * @var EventService
     */
    private $eventService;


    /**
     * EventController constructor.
     */
    public function __construct(EventService $eventService) {
        $this->eventService = $eventService;
    }

    public function get(Request $request) {
        $events = Event::query()
            ->when($request->not_expired, function ($query, $notExpired) {
                $query->where('end_at', '>', now())
                    ->orderBy('begin_at', 'asc');
            })
            ->when($request->expired, function ($query, $notExpired) {
                $query->where('end_at', '<', now())
                    ->orderBy('begin_at', 'desc');
            })
            ->when($request->with_trashed === 'true', function ($query) {
                $query->withTrashed();
            })
            ->orderBy('begin_at', 'asc')
            ->get();

        return compact('events');
    }

    public function getOne(Event $event) {
        $event->load('gallery.gallery_pictures');

        return compact('event');
    }

    public function store(CreateEventRequest $request) {
        $event = $this->eventService->create($request)
            ->load('author');

        return compact('event');
    }

    public function update(UpdateEventRequest $request, Event $event) {
        $this->eventService->update($event, $request);

        return compact('event');
    }

    public function destroy(DeleteEventRequest $request, Event $event) {
        $event->delete();

        return response()->noContent();
    }

    public function restore(DeleteEventRequest $request, $event) {
        $event = Event::withTrashed()->whereSlug($event)->first();
        $event->restore();

        return compact('event');
    }
}
