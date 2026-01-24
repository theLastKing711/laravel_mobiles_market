<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MobileOfferFeature>
 */
class MobileOfferFeatureFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->name,
        ];
    }
}

// return [
//             'exam_students.*.student_id' => [
//                 function (string $attribute, mixed $value, Closure $fail) use ($exam_course_students_ids) {

//                     $student_has_registered_in_exam_course =
//                         $exam_course_students_ids
//                             ->contains($value);

//                     if (! $student_has_registered_in_exam_course) {
//                         $fail($attribute,
//                             __(
//                                 'messages.exam_students.student unregistered in course',
//                                 [
//                                     'id' => $value,
//                                 ]
//                             )
//                         );
//                     }

//                 },
//             ],
//         ];
