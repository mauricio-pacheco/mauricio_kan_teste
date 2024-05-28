<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Boleto;

class CreateBoletosTable extends Migration
{
    public function up()
    {
        Schema::create('boletos', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('government_id');
            $table->string('email');
            $table->decimal('debt_amount', 10, 2);
            $table->date('due_date');
            $table->integer('debt_id');
            $table->timestamps();
        });

        Boleto::create([
            'name' => 'John Doe',
            'government_id' => '123456789',
            'email' => 'johndoe@example.com',
            'debt_amount' => 1000.00,
            'due_date' => '2023-12-31',
            'debt_id' => 1,
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('boletos');
    }
}
