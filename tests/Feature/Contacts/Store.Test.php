<?php


uses(Illuminate\Foundation\Testing\WithFaker::class);

it('can store a contact', function () {
    $user = \App\Models\User::factory()->create([
        'account_id' => \App\Models\Account::factory()->create()->id,
    ]);

    $this->actingAs($user)
        ->post('/contacts', [
            'first_name' => $this->faker->firstName,
            'last_name' => $this->faker->lastName,
            'email' => $this->faker->email,
            'phone' => $this->faker->phoneNumber,
            'address' => '1 Test Street',
            'city' => 'Testerfield',
            'region' => 'Derbyshire',
            'country' => $this->faker->randomElement(['us', 'ca']),
            'postal_code' => $this->faker->postcode,
        ])
        ->assertRedirect('/contacts')
        ->assertSessionHas('success', 'Contact created');
});
