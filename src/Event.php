<?php

namespace Belt\Spot;

use Belt;
use Belt\Content\Handle;
use Belt\Content\Section;
use Illuminate\Database\Eloquent\Model;

/**
 * Class Event
 * @package Belt\Spot
 */
class Event extends Model implements
    Belt\Core\Behaviors\IsSearchableInterface,
    Belt\Core\Behaviors\ParamableInterface,
    Belt\Core\Behaviors\PriorityInterface,
    Belt\Core\Behaviors\SluggableInterface,
    Belt\Core\Behaviors\TeamableInterface,
    Belt\Core\Behaviors\TypeInterface,
    Belt\Content\Behaviors\HandleableInterface,
    Belt\Content\Behaviors\HasSectionsInterface,
    Belt\Content\Behaviors\IncludesContentInterface,
    Belt\Content\Behaviors\IncludesSeoInterface,
    Belt\Content\Behaviors\IncludesTemplateInterface,
    Belt\Content\Behaviors\TermableInterface,
    Belt\Content\Behaviors\ClippableInterface,
    Belt\Spot\Behaviors\LocatableInterface,
    Belt\Spot\Behaviors\RateableInterface
{
    use Belt\Core\Behaviors\HasSortableTrait;
    use Belt\Core\Behaviors\IsSearchable;
    use Belt\Core\Behaviors\PriorityTrait;
    use Belt\Core\Behaviors\Sluggable;
    use Belt\Core\Behaviors\Teamable;
    use Belt\Core\Behaviors\TypeTrait;
    use Belt\Content\Behaviors\Clippable;
    use Belt\Content\Behaviors\Handleable;
    use Belt\Content\Behaviors\HasSections;
    use Belt\Content\Behaviors\IncludesContent;
    use Belt\Content\Behaviors\IncludesSeo;
    use Belt\Content\Behaviors\IncludesTemplate;
    use Belt\Content\Behaviors\Termable;
    use Belt\Spot\Behaviors\Locatable;
    use Belt\Spot\Behaviors\Rateable;

    /**
     * @var string
     */
    protected $morphClass = 'events';

    /**
     * @var string
     */
    protected $table = 'events';

    /**
     * @var array
     */
    protected $fillable = ['name'];

    /**
     * @var array
     */
    protected $dates = ['starts_at', 'ends_at', 'created_at', 'updated_at', 'deleted_at'];

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
        $array['location'] = $this->location;

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
        $array['location'] = $this->location ? $this->location->latLng : null;

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
     * @param $event
     * @return Model
     */
    public static function copy($event)
    {
        $event = $event instanceof Event ? $event : self::sluggish($event)->first();

        $clone = $event->replicate();
        $clone->setIsCopy(true);
        $clone->slug .= '-' . strtotime('now');
        $clone->push();

        foreach ($event->locations as $location) {
            Location::copy($location, ['locatable_id' => $clone->id]);
        }

        foreach ($event->sections as $section) {
            Section::copy($section, ['owner_id' => $clone->id]);
        }

        foreach ($event->attachments as $attachment) {
            $clone->attachments()->attach($attachment);
        }

        foreach ($event->handles as $handle) {
            Handle::copy($handle, ['handleable_id' => $clone->id]);
        }

        foreach ($event->terms as $term) {
            $clone->terms()->attach($term);
        }

        foreach ($event->params as $param) {
            $clone->saveParam($param->key, $param->value);
        }

        return $clone;
    }
}