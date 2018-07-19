@php
    $place = $place ?? $owner ?? new \Belt\Spot\Place();
@endphp

<div class="place">

    @foreach($place->sections as $section)
        @include($section->subtype_view, ['section' => $section])
    @endforeach

</div>