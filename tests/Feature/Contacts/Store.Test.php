<?php

use App\Models\Contact;
use Illuminate\Foundation\Testing\WithFaker;

uses(WithFaker::class);
it('can store a contact', function ($email) {
    $faker = fake();
    login()->post('/contacts', [
        'first_name' => $faker->firstName,
        'last_name' => $faker->lastName,
        'email' => $email,
        'phone' => '+9237457384',
        'address' => '1 Test Street',
        'city' => 'TesterField',
        'region' => 'Derby shire',
        'country' => $faker->randomElement(['us', 'ca']),
        'postal_code' => $faker->postcode,
    ])->assertRedirect('/contacts')->assertSessionHas('success', 'Contact created.');

    $contact = \App\Models\Contact::latest()->first();

    expect($contact->first_name)->toBeString()->not->toBeEmpty();
    expect($contact->last_name)->toBeString()->not->toBeEmpty();
    expect($contact->city)->toBe('TesterField');
    expect($contact->phone)->toBePhoneNumber();
})->with('valid emails');
