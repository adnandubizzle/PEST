<?php


uses(Illuminate\Foundation\Testing\Withfaker::class);

it('can store a contact', function () {
    login()->post('/contacts', [
        'first_name' => $this->faker->firstName,
        'last_name' => $this->faker->last_name,
        'email' => $this->faker->email,
        'phone' => $this->faker->phone,
        'address' => '1 Test Streeet',
        'city' => 'Testerfield',
        'reigion' => 'Derbyshire',
        'country' => $this->faker->randomElement(['us', 'ca']),
        'postal_code' => $this->faker->postal_code,


    ])->assertReddirect('/contacts')->assertSessionHas('Success', 'Contact creted');
});
