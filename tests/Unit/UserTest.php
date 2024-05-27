<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;

class UserTest extends TestCase
{
    /**
     *
     * @return void
     */
    public function testCreateUser()
    {
        $user = new User();
        $user->name = 'Maurício Pacheco';
        $user->email = 'email@email_teste.com';

        $this->assertEquals('Maurício Pacheco', $user->name);
        $this->assertEquals('email@email_teste.com', $user->email);
    }
}
