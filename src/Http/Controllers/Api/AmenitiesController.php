<?php

namespace Belt\Spot\Http\Controllers\Api;

use Belt\Core\Http\Controllers\ApiController;
use Belt\Spot\Amenity;
use Belt\Spot\Http\Requests;

class AmenitiesController extends ApiController
{

    /**
     * @var Amenity
     */
    public $amenity;

    /**
     * ApiController constructor.
     * @param Amenity $amenity
     */
    public function __construct(Amenity $amenity)
    {
        $this->amenities = $amenity;
    }

    public function get($id)
    {
        return $this->amenities->find($id) ?: $this->abort(404);
    }

    /**
     * Display a listing of the resource.
     *
     * @param $request
     * @return \Illuminate\Http\Response
     */
    public function index(Requests\PaginateAmenities $request)
    {
        $this->authorize('index', Amenity::class);

        $paginator = $this->paginator($this->amenities->query(), $request->reCapture());

        return response()->json($paginator->toArray());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  Requests\StoreAmenity $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Requests\StoreAmenity $request)
    {
        $this->authorize('create', Amenity::class);

        $input = $request->all();

        $amenity = $this->amenities->create([
            'name' => $request->get('name'),
        ]);

        $this->set($amenity, $input, [
            'slug',
            'body',
        ]);

        $amenity->save();

        return response()->json($amenity, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  Amenity $amenity
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Amenity $amenity)
    {
        $this->authorize('view', $amenity);

        return response()->json($amenity);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  Requests\UpdateAmenity $request
     * @param  Amenity $amenity
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Requests\UpdateAmenity $request, Amenity $amenity)
    {
        $this->authorize('update', $amenity);

        $input = $request->all();

        $this->set($amenity, $input, [
            'name',
            'slug',
            'body',
        ]);

        $amenity->save();

        return response()->json($amenity);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  Amenity $amenity
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Amenity $amenity)
    {
        $this->authorize('delete', $amenity);

        $amenity->delete();

        return response()->json(null, 204);
    }
}