<?php

namespace App\Models;

use App\Enum\Language;
use CloudinaryLabs\CloudinaryLaravel\MediaAlly;
use Illuminate\Database\Eloquent\Attributes\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\MorphOne;
use Illuminate\Support\Facades\Auth;
use Laravel\Scout\Searchable;

class MobileOffer extends Model
{
    /** @use HasFactory<\Database\Factories\MobileOfferFactory> */
    use HasFactory, MediaAlly, Searchable;

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo<MobileOffer, $this>
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<MobileOfferFeature, $this>
     */
    public function features(): BelongsToMany
    {
        return $this->belongsToMany(MobileOfferFeature::class, 'mobile_feature');
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\MorphOne<Media, $this>
     */
    public function mainImage(): MorphOne
    {
        return $this->medially()->one();
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany<User, $this, UserFavouritesMobileOffer>
     */
    public function favouriteByUsers(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'user_favourites_mobile_offer');
    }

    #[Scope]
    protected function sold(Builder $query): void
    {
        $query
            ->where('is_sold', true);
    }

    #[Scope]
    protected function notSold(Builder $query): void
    {
        $query
            ->where('is_sold', false);
    }

    // #[SearchUsingFullText(['model', 'description'])]
    // the data that get sent to the index (saved there)
    // we can retrive all of them using Model::search()
    // not what to filter on when using model::search()
    public function toSearchableArray(): array
    {

        $index_attributes_array =
            // array_merge(
                // $this->toArray(),
                [
                    'id' => (string) $this->id,
                    'user_id' => $this->user_id ?? Auth::User()->id,
                    'name_in_english' => $this->name_in_english,
                    'name_in_arabic' => $this->name_in_arabic,
                    // 'mobile_name_language_when_uploaded' => $this->mobile_name_language_when_uploaded,
                    // 'price_in_usd' => $this->price_in_usd,
                    // 'is_sold' => $this->is_sold,
                    // // 'screen_size' => $this->screen_size,
                    // // 'screen_type' => $this->screen_type,
                    // // 'cpu' => $this->cpu,
                    // 'ram' => $this->ram,
                    // 'storage' => $this->storage,
                    // // 'battery_size' => $this->battery_size,
                    // 'battery_health' => $this->battery_health,
                    // 'number_of_sims' => $this->number_of_sims,
                    // 'number_of_esims' => $this->number_of_esims,
                    // 'color' => $this->color,
                    // 'created_at' => strtotime($this->created_at),
                ];
        // );

        // $index_attributes_array['manufacturer_id'] =
        //     $this->Manufacturer->id; // foriegn key, it works but its not needed here

        // load shippable city to this remote table(cars) index
        // $index_attributes_array['city'] =
        //     $this->shippable_to
        //         ->map(function ($data) {
        //             return $data['city'];
        //         });

        // $index_attributes_array['favourited_by_users'] =
        //     $this->favourited_by_users
        //         ->map(function ($data) {
        //             return $data['id'];
        //         });
        return $index_attributes_array;
    }

    protected function casts(): array
    {
        return [
            'mobile_name_language_when_uploaded' => Language::class,
        ];
    }

    // add to remote search index if it does return true
    // public function shouldBeSearchable()
    // {
    //     $is_mobile_not_sold = ! $this->is_sold;

    //     return $is_mobile_not_sold;
    // }
}
