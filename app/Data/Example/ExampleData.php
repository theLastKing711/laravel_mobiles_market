<?php
namespace App\Data\Example;

use App\Data\Shared\Swagger\Property\ArrayProperty;
use App\Models\User;
use Illuminate\Support\Collection;
use Spatie\TypeScriptTransformer\Attributes\TypeScript;
use OpenApi\Attributes as OAT;
use Spatie\LaravelData\Data;

#[TypeScript]
#[Oat\Schema]
class ExampleData extends Data
{

    /** @param Collection<int, CreateOrderDetailsData> $ids*/
    public function __construct(
        #[OAT\Property()]
        public int $id,
        #[ArrayProperty()]
        public Collection $ids,
    ) {
    }

    // manual conversion
    // public static function fromModel(User $order): self
    // {

    //     return new self(
    //         id: $order->id,
    //         customer_name: $order->user->name,
    //         driver_name: $order->driver->name,
    //         total: $order->total,
    //         status: OrderStatus::from($order->status),
    //     );
    // }

    // public static function rules()
    // {
    //     // App::setLocale('ar');

    //     /** @var Setting $setting */
    //     $setting = Setting::first();

    //     $settings_min_item_per_order =
    //         $setting
    //             ->order_delivery_min_item_per_order;

    //     /** @var Collection<int, string> $active_products_Ids */
    //     $active_products_Ids =
    //         Product::query()
    //             ->whereIsActive(true)
    //             ->get()
    //             ->pluck('id');

    //     return [
    //         'order_details' => ['array', 'required'],
    //         'order_details.*.quantity' => 'min:'.$settings_min_item_per_order,
    //         'order_details.*.product_id' => Rule::in($active_products_Ids),
    //     ];
    // }

    // public static function messages()
    // {
    //     return [
    //         'order_details.*.product_id.in' => __('validation.user.product.quantity.out_of_stock'),
    //     ];
    // }


}
