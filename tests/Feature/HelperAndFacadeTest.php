<?php

it('helper gets and sets values', function () {
    settings(['helper.demo' => 'yes']);
    expect(settings('helper.demo'))->toBe('yes');
    expect(settings('helper.unknown', 'fallback'))->toBe('fallback');
});
