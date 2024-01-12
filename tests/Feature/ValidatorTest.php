<?php

namespace Tests\Feature;

use App\Rules\RegistrationRule;
use App\Rules\Upercase;
use Closure;
use Tests\TestCase;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;
use Illuminate\Validation\Validator as ValidationValidator;

class ValidatorTest extends TestCase
{
    public function test_validator_success () : void
    {
        $data = [
            'username'   => 'admin',
            'password'  => '12345678'
        ];

        $rules = [
            'username'  => 'required',
            'password'  => 'required'
        ];

        $validator = Validator::make($data, $rules);
        $this->assertTrue($validator->passes());
        $this->assertFalse($validator->fails());
    }

    public function test_validator_invalid () : void
    {
        $data = [
            'uername'   => '',
            'password'  => ''
        ];

        $rules = [
            'username'  => 'required',
            'password'  => 'required'
        ];
        $validator = Validator::make($data, $rules);
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());
    }

    public function test_validator_invalid_with_message () : void
    {
        $data = [
            'uername'   => '',
            'password'  => ''
        ];

        $rules = [
            'username'  => 'required',
            'password'  => 'required'
        ];
        $validator = Validator::make($data, $rules);
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());

        $messageBag = $validator->getMessageBag();
        $messages = $validator->messages();
        $errors = $validator->errors();

        Log::info('menggunakan messageBag ' . $messageBag->toJson(JSON_PRETTY_PRINT));
        Log::info('menggunakan messages ' . $messages->toJson(JSON_PRETTY_PRINT));
        Log::info('menggunakan errors ' . $errors->toJson(JSON_PRETTY_PRINT));
    }

    public function test_validator_invalid_validation_exception () : void
    {
        $data = [
            'uername'   => '',
            'password'  => ''
        ];
        $rules = [
            'username'  => 'required',
            'password'  => 'required'
        ];
        $validator = Validator::make($data, $rules);

        try {
            // $validator->validated();
            $validator->validate();
            // akan mengembalikan exception lalu di tangkap dengan try catch
            $this->fail('ValidationException not thrown');
        } catch (ValidationException $exception) {
            $this->assertNotNull($exception->errors());
            $messages = $exception->validator->errors();
            // kembalianya messagesbag
            Log::info("using ValidationException " . $messages->toJson(JSON_PRETTY_PRINT));
        }
    }

    public function test_validation_rules_invalid () : void
    {
        $data = [
            'username' => 'james',
            'password' => '123'
        ];

        $rules = [
            'username' => ['required', 'min:3', 'max:20'],
            'password' => ['required', 'min:8', 'max:16']
        ];

        $validator = Validator::make($data, $rules);

        $this->assertTrue($validator->fails());
        var_dump($validator->errors()->toJson(JSON_PRETTY_PRINT));
        Log::info($validator->errors()->toJson(JSON_PRETTY_PRINT));
    }

    public function test_valid_data () : void
    {
        $data = [
            'username' => 'james',
            'password' => '12345678',
            'is_admin' => true
        ];

        $rules = [
            'username' => ['required', 'min:3', 'max:20'],
            'password' => ['required', 'min:8', 'max:16']
        ];

        $validator = Validator::make($data, $rules);

        try {
            // $result = $validator->validate();
            $result = $validator->validated();
            $this->assertNotNull($result);
            var_dump($result);
            Log::info('tidak error ' . json_encode($result, JSON_PRETTY_PRINT));
        } catch (ValidationException $exception) {
            $this->fail($exception->getMessage());
        }
    }

    public function test_validation_costum_message_lang_id () : void
    {

        $data = [
            'username' => 'james',
            'password' => '12345678',
            'is_admin' => true
        ];

        $rules = [
            'username' => ['required', 'min:3', 'max:20', 'email'],
            'password' => ['required', 'min:8', 'max:16']
        ];

        $validator = Validator::make($data, $rules);
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());

        var_dump("message bag" . $validator->getMessageBag()->toJson(JSON_PRETTY_PRINT));
        Log::info("message bag" . $validator->getMessageBag()->toJson(JSON_PRETTY_PRINT));
    }

    public function test_validation_costum_message_lang_en () : void
    {
        App::setLocale('en');
        App::setFallbackLocale('en');

        $data = [
            'username' => 'james',
            'password' => '12345678',
            'is_admin' => true
        ];

        $rules = [
            'username' => ['required', 'min:3', 'max:20', 'email'],
            'password' => ['required', 'min:8', 'max:16']
        ];

        $validator = Validator::make($data, $rules);
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());

        var_dump("message bag" . $validator->getMessageBag()->toJson(JSON_PRETTY_PRINT));
        Log::info("message bag" . $validator->getMessageBag()->toJson(JSON_PRETTY_PRINT));
    }

    public function test_validation_costum_message_inline_message () : void
    {
        $data = [
            'username' => '',
            'password' => ''
        ];

        $rules = [
            'username' => ['required', 'min:3', 'max:20', 'email'],
            'password' => ['required', 'min:8', 'max:16']
        ];

        $message = [
            'required'  => ':attribute wajib diisi',
            'email'     => ':atteibute wajib email yang valid',
            'max'       => ':attribute maximal :max karakter',
            'min'       => ':attribute minimal :min karakter'
        ];

        $validator = Validator::make($data, $rules, $message);
        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());

        var_dump("message bag" . $validator->getMessageBag()->toJson(JSON_PRETTY_PRINT));
        Log::info("message bag" . $validator->getMessageBag()->toJson(JSON_PRETTY_PRINT));
    }

    public function test_additional_validation () : void
    {
        $data = [
            'username' => 'eriktenis',
            'password' => 'eriktenis'
        ];

        $rules = [
            'username' => ['required', 'min:3', 'max:20'],
            'password' => ['required', 'min:8', 'max:16']
        ];

        $validator = Validator::make($data, $rules);
        // $this->assertTrue($validator->fails());
        var_dump($validator->getData());
        $this->assertTrue($validator->passes());
        $validator->after( function (ValidationValidator $validator) {
            $data = $validator->getData();
            if ($data['username'] == $data['password']) {
                $validator->errors()->add('password', 'password tidak boleh sama dengan username');
            }
        });

        if ($validator->fails()) {
            Log::info($validator->getMessageBag()->toJson(JSON_PRETTY_PRINT));
        }
    }

    public function test_costum_role_validate () : void
    {
        $data = [
            'username' => 'eriktenis',
            'password' => 'eriktenis'
        ];

        $rules = [
            'username' => ['required', 'min:3', 'max:20', new Upercase],
            'password' => ['required', 'min:8', 'max:16', new RegistrationRule]
        ];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());

        var_dump('message bag' . $validator->getMessageBag()->toJson(JSON_PRETTY_PRINT));
        Log::info('messagebag' . $validator->getMessageBag()->toJson(JSON_PRETTY_PRINT));
    }

    public function test_costum_role_function_validation () : void
    {
        $data = [
            'username' => 'eriktenis',
            'password' => 'eriktenis'
        ];

        $rules = [
            'username' => ['required', 'min:3', 'max:20',
             function (string $attribute, mixed $value, Closure $fail) {
                if (strtoupper($value) !== $value) {
                    $fail('validation.uppercase')->translate([
                        'attribute' => $attribute,
                        'value'     => $value
                    ]);
                }
             }
            ],
            'password' => ['required', 'min:8', 'max:16', new RegistrationRule]
        ];

        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());

        var_dump('message bag' . $validator->getMessageBag()->toJson(JSON_PRETTY_PRINT));
        Log::info('messagebag' . $validator->getMessageBag()->toJson(JSON_PRETTY_PRINT));
    }

    public function test_validation_rules_classes () : void
    {
        $data = [
            'username' => 'eriktenis',
            'password' => '123awe?<'
        ];

        $rules = [
            'username' => ['required', 'min:3', 'max:20',
             function (string $attribute, mixed $value, Closure $fail) {
                if (strtoupper($value) !== $value) {
                    $fail('validation.uppercase')->translate([
                        'attribute' => $attribute,
                        'value'     => $value
                    ]);
                }
             }
            ],
            'password' => ['required', 'min:8', 'max:16', new RegistrationRule, Password::min(8)->letters()->numbers()->symbols()->mixedCase()]
        ];
        $validator = Validator::make($data, $rules);

        $this->assertFalse($validator->passes());
        $this->assertTrue($validator->fails());

        var_dump('message bag' . $validator->getMessageBag()->toJson(JSON_PRETTY_PRINT));
        Log::info('messagebag' . $validator->getMessageBag()->toJson(JSON_PRETTY_PRINT));
    }

    public function test_validation_nested_array () : void
    {
        $data = [
            'name' => [
                'first' => 'jufron',
                'last'  => 'tamo ama'
            ],
            'address' => [
                'street'    => 'jl tasek',
                'city'      => 'kupang city',
                'country'   => 'indonesia',
            ]
        ];

        $rules = [
            'name.first'        => ['required', 'max:100'],
            'name.last'         => ['required', 'max:100'],
            'address.street'    => ['required', 'max:100'],
            'address.city'      => ['required', 'max:100'],
            'address.country'   => ['required', 'max:100']
        ];

        $validator = Validator::make($data, $rules);
        $this->assertTrue($validator->passes());
        $this->assertFalse($validator->fails());
        var_dump($validator->getData());
    }

    public function test_validation_indexed_nested_array () : void
    {
        $data = [
            'name' => [
                'first' => 'jufron',
                'last'  => 'tamo ama'
            ],
            'address' => [
                [
                    'street'    => 'jl tasek',
                    'city'      => 'kupang city',
                    'country'   => 'indonesia',
                ],
                [
                    'street'    => 'jl muhamadia',
                    'city'      => 'jakarta city',
                    'country'   => 'indonesia',
                ]
            ]
        ];

        $rules = [
            'name.*.first'        => ['required', 'max:100'],
            'name.*.last'         => ['required', 'max:100'],
            'address.*.street'    => ['required', 'max:100'],
            'address.*.city'      => ['required', 'max:100'],
            'address.*.country'   => ['required', 'max:100']
        ];

        $validator = Validator::make($data, $rules);
        $this->assertTrue($validator->passes());
        $this->assertFalse($validator->fails());
        var_dump($validator->getData());
    }

    public function test_request_validation_success () : void
    {
        $this->post('form/login', [
            'username'  => 'james',
            'password'  => '12345678'
        ])
        ->assertStatus(200)
        ->assertOk()
        ->assertSuccessful();
    }

    public function test_request_validation_failed () : void
    {
        $this->post('form/login', [
            'username'  => '',
            'password'  => ''
        ])
        ->assertStatus(400)
        ->assertBadRequest();
    }

    public function test_form_success () : void
    {
        $this->post('login', [
            'username'  => 'james',
            'password'  => '1234aaA?>'
        ])
        ->assertStatus(200)
        ->assertSuccessful()
        ->assertOk();
    }

    public function test_form_failed () : void
    {
        $this->post('login', [
            'username'  => '',
            'password'  => ''
        ])
        ->assertStatus(302);
    }
}
