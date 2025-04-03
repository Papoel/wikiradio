<?php

namespace App\Tests\Unit;

test('true_is_true', function () {
    expect(true)->toBeTrue();
});

test('array_can_be_counted', function () {
    $array = [1, 2, 3];
    expect($array)->toHaveCount(3);
});

test('strings_can_be_concatenated', function () {
    $string1 = "Wiki";
    $string2 = "Radio";
    
    expect($string1 . $string2)->toBe("WikiRadio");
});
