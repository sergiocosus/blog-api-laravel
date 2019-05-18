<?php
/**
 * Created by PhpStorm.
 * User: sergio
 * Date: 31/08/18
 * Time: 12:53 PM
 */

namespace App\Core\Event;


use Illuminate\Http\Request;

class EventService {

    public function __construct() {
    }

    public function create(Request $request) {
        $event = new Event();
        $event->author()->associate($request->user());
        $this->update($event, $request);

        return $event;
    }

    public function update(Event $event, Request $request) {
        \DB::transaction(function () use ($request, $event) {
            $event->fill($request->only([
                'title',
                'description',
                'address',
                'begin_at',
                'end_at',
                'notify_at',
                'latitude',
                'longitude',
            ]))->save();

            if ($request->picture) {
                $event->addMediaFromBase64($request->picture['base64'])
                    ->preservingOriginal()
                    ->setName($request->picture['name'])
                    ->toMediaCollection('main');;
            }
        });

        return $event;
    }
}
