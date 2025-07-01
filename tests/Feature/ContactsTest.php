<?php

namespace Tests\Feature;

use App\Models\Account;
use App\Models\User;
use Inertia\Testing\AssertableInertia as Assert;
use Tests\TestCase;

class ContactsTest extends TestCase
{
    protected $user;

    protected function setUp(): void
    {
        parent::setUp();
    }

    public function test_can_view_contacts()
    {
        $this->actingAs($this->user)
            ->get('/contacts')
            ->assertInertia(
                fn(Assert $assert) => $assert
                    ->component('Contacts/Index')
                    ->has('contacts.data', 2)
                    ->has(
                        'contacts.data.0',
                        fn(Assert $assert) => $assert
                            ->where('id', 1)
                            ->where('name', 'Martin Abbott')
                            ->where('phone', '555-111-2222')
                            ->where('city', 'Murphyland')
                            ->where('deleted_at', null)
                            ->has(
                                'organization',
                                fn(Assert $assert) => $assert
                                    ->where('name', 'Example Organization Inc.')
                            )
                    )
                    ->has(
                        'contacts.data.1',
                        fn(Assert $assert) => $assert
                            ->where('id', 2)
                            ->where('name', 'Lynn Kub')
                            ->where('phone', '555-333-4444')
                            ->where('city', 'Woodstock')
                            ->where('deleted_at', null)
                            ->has(
                                'organization',
                                fn(Assert $assert) => $assert
                                    ->where('name', 'Example Organization Inc.')
                            )
                    )
            );
    }

    public function test_can_search_for_contacts()
    {
        $this->actingAs($this->user)
            ->get('/contacts?search=Martin')
            ->assertInertia(
                fn(Assert $assert) => $assert
                    ->component('Contacts/Index')
                    ->where('filters.search', 'Martin')
                    ->has('contacts.data', 1)
                    ->has(
                        'contacts.data.0',
                        fn(Assert $assert) => $assert
                            ->where('id', 1)
                            ->where('name', 'Martin Abbott')
                            ->where('phone', '555-111-2222')
                            ->where('city', 'Murphyland')
                            ->where('deleted_at', null)
                            ->has(
                                'organization',
                                fn(Assert $assert) => $assert
                                    ->where('name', 'Example Organization Inc.')
                            )
                    )
            );
    }

    public function test_cannot_view_deleted_contacts()
    {
        $this->user->account->contacts()->firstWhere('first_name', 'Martin')->delete();

        $this->actingAs($this->user)
            ->get('/contacts')
            ->assertInertia(
                fn(Assert $assert) => $assert
                    ->component('Contacts/Index')
                    ->has('contacts.data', 1)
                    ->where('contacts.data.0.name', 'Lynn Kub')
            );
    }

    public function test_can_filter_to_view_deleted_contacts()
    {
        $this->user->account->contacts()->firstWhere('first_name', 'Martin')->delete();

        $this->actingAs($this->user)
            ->get('/contacts?trashed=with')
            ->assertInertia(
                fn(Assert $assert) => $assert
                    ->component('Contacts/Index')
                    ->has('contacts.data', 2)
                    ->where('contacts.data.0.name', 'Martin Abbott')
                    ->where('contacts.data.1.name', 'Lynn Kub')
            );
    }
}
