<?php

namespace Belt\Spot;

use Belt;
use Belt\Content\Handle;
use Belt\Content\Section;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Place
 * @package Belt\Spot
 */
class Place extends Model implements
    Belt\Core\Behaviors\IsSearchableInterface,
    Belt\Core\Behaviors\ParamableInterface,
    Belt\Core\Behaviors\SluggableInterface,
    Belt\Core\Behaviors\TeamableInterface,
    Belt\Core\Behaviors\TypeInterface,
    Belt\Clip\Behaviors\ClippableInterface,
    Belt\Content\Behaviors\HandleableInterface,
    Belt\Content\Behaviors\HasSectionsInterface,
    Belt\Content\Behaviors\IncludesContentInterface,
    Belt\Content\Behaviors\IncludesSeoInterface,
    Belt\Content\Behaviors\IncludesTemplateInterface,
    Belt\Glue\Behaviors\CategorizableInterface,
    Belt\Glue\Behaviors\TaggableInterface,
    Belt\Spot\Behaviors\AddressableInterface,
    Belt\Spot\Behaviors\HasAmenitiesInterface,
    Belt\Spot\Behaviors\RateableInterface
{
    use Belt\Core\Behaviors\HasSortableTrait;
    use Belt\Core\Behaviors\IsSearchable;
    use Belt\Core\Behaviors\Sluggable;
    use Belt\Core\Behaviors\Teamable;
    use Belt\Core\Behaviors\TypeTrait;
    use Belt\Clip\Behaviors\Clippable;
    use Belt\Content\Behaviors\HasSections;
    use Belt\Content\Behaviors\Handleable;
    use Belt\Content\Behaviors\IncludesContent;
    use Belt\Content\Behaviors\IncludesSeo;
    use Belt\Content\Behaviors\IncludesTemplate;
    use Belt\Glue\Behaviors\Categorizable;
    use Belt\Glue\Behaviors\Taggable;
    use Belt\Spot\Behaviors\Addressable;
    use Belt\Spot\Behaviors\HasAmenities;
    use Belt\Spot\Behaviors\Rateable;

    /**
     * @var string
     */
    protected $morphClass = 'places';

    /**
     * @var string
     */
    protected $table = 'places';

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @var array
     */
    protected $with = ['handles'];

    /**
     * @var array
     */
    protected $appends = ['image', 'type', 'default_url', 'morph_class'];

    /**
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray(); // TODO[crowe]: Change the autogenerated stub
        $array['address'] = $this->address;

        return $array;
    }

    /**
     * Get the indexable data array for the model.
     *
     * @return array
     */
    public function toSearchableArray()
    {
        $array = $this->__toSearchableArray();
        $array['categories'] = $this->categories ? $this->categories->pluck('id')->all() : null;
        $array['tags'] = $this->tags ? $this->tags->pluck('id')->all() : null;
        $array['location'] = $this->address ? $this->address->latLng : null;

        return $array;
    }

    /**
     * @todo deprecate
     * @var array
     */
    public static $presets = [
        [100, 100, 'fit'],
        [222, 222, 'resize'],
        [333, 333, 'resize'],
        [500, 500, 'resize'],
    ];

    /**
     * @param $value
     */
    public function setIsSearchableAttribute($value)
    {
        $this->attributes['is_searchable'] = boolval($value);
    }

    /**
     * @param $place
     * @return Model
     */
    public static function copy($place)
    {
        $place = $place instanceof Place ? $place : self::sluggish($place)->first();

        $clone = $place->replicate();
        $clone->slug .= '-' . strtotime('now');
        $clone->push();

        foreach ($place->addresses as $address) {
            Address::copy($address, ['addressable_id' => $clone->id]);
        }

        foreach ($place->sections as $section) {
            Section::copy($section, ['owner_id' => $clone->id]);
        }

        foreach ($place->attachments as $attachment) {
            $clone->attachments()->attach($attachment);
        }

        foreach ($place->categories as $category) {
            $clone->categories()->attach($category);
        }

        foreach ($place->handles as $handle) {
            Handle::copy($handle, ['handleable_id' => $clone->id]);
        }

        foreach ($place->tags as $tag) {
            $clone->tags()->attach($tag);
        }

        return $clone;
    }
}