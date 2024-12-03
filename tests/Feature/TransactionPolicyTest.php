<?php

it('has transactionpolicy page', function () {
    $response = $this->get('/transactionpolicy');

    $response->assertStatus(200);
});
