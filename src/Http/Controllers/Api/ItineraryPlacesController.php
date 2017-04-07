<?php

namespace Belt\Spot\Http\Controllers\Api;

use Belt\Core\Http\Controllers\ApiController;
use Belt\Core\Http\Controllers\Behaviors\Positionable;
use Belt\Spot\Itinerary;
use Belt\Spot\ItineraryPlace;
use Belt\Spot\Place;
use Belt\Spot\Http\Requests;
use Illuminate\Http\Request;

class ItineraryPlacesController extends ApiController
{

    use Positionable;

    /**
     * @var Itinerary
     */
    public $itineraryPlace;

    public function __construct(ItineraryPlace $itineraryPlace, Place $place)
    {
        $this->itineraryPlace = $itineraryPlace;
        $this->place = $place;
    }

    public function contains($itinerary, $id)
    {
        if (!$itinerary->places->contains($id)) {
            $this->abort(404, 'itinerary does not have this place');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @param Requests\PaginateItineraryPlaces $request
     * @param Itinerary $itinerary
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\PaginateItineraryPlaces $request, $itinerary)
    {
        $request->reCapture();

        $request->merge(['itinerary_id' => $itinerary->id]);

        $this->authorize('view', $itinerary);

        $paginator = $this->paginator($this->itineraryPlace->query(), $request);

        return response()->json($paginator->toArray());
    }

    /**
     * Store a newly created resource in glue.
     *
     * @param  Requests\StoreItineraryPlace $request
     * @param Itinerary $itinerary
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\StoreItineraryPlace $request, $itinerary)
    {
        $this->authorize('update', $itinerary);

        $place_id = $request->get('place_id');

        if ($itinerary->places->contains($place_id)) {
            $this->abort(422, ['id' => ['place already attached']]);
        }

        $itineraryPlace = $this->itineraryPlace->create([
            'itinerary_id' => $itinerary->id,
            'place_id' => $place_id,
        ]);

        $input = $request->all();

        $this->set($itineraryPlace, $input, [
            'heading',
            'body',
        ]);

        $itineraryPlace->save();

        return response()->json($itineraryPlace, 201);
    }

    /**
     * Store a newly created resource in glue.
     *
     * @param Request $request
     * @param Itinerary $itinerary
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $itinerary, $id)
    {
        $this->authorize('update', $itinerary);

        $this->contains($itinerary, $id);

        $itineraryPlace = $this->itineraryPlace->find($id);

        $input = $request->all();

        $this->set($itineraryPlace, $input, [
            'heading',
            'body',
        ]);

        $itineraryPlace->save();

        $this->reposition($request, $itineraryPlace);

        return response()->json($itineraryPlace);
    }

    /**
     * Display the specified resource.
     *
     * @param Itinerary $itinerary
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function show($itinerary, $id)
    {
        $this->authorize('view', $itinerary);

        $this->contains($itinerary, $id);

        $itineraryPlace = $this->itineraryPlace->find($id);

        return response()->json($itineraryPlace);
    }

    /**
     * Remove the specified resource from glue.
     *
     * @param Itinerary $itinerary
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($itinerary, $id)
    {

        $this->authorize('update', $itinerary);

        $this->contains($itinerary, $id);

        $itineraryPlace = $this->itineraryPlace->find($id);

        $itineraryPlace->delete();

        return response()->json(null, 204);
    }
}