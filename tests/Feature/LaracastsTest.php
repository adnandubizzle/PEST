
<?php

uses()->group('Laracasts');

it('can validate an email', function () {
    $rule = new \App\Rules\IsValidEmailAddress();
    expect($rule->passes('email', 'me@you.com'))->toBeTrue();
});

it('throws an exception if the value is not a string', function () {
    $rule = new \App\Rules\IsValidEmailAddress();
    $rule->passes('email', 1);
})->skip('we no longer want to test the exception.')
    ->throws(InvalidArgumentException::class, 'The value must be a string!')
    ->group('current');

it('has better regex support and can catch more email addresses');
