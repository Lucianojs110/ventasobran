<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSucursalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sucursals', function (Blueprint $table) {
            $table->bigIncrements('id');

            $table->string('nombre', 500);
            $table->string('direccion', 500);
            $table->string('ciudad', 500);
            $table->string('cuit', 500);
            $table->string('ingresos_brutos', 500);
            $table->string('telefono', 500);
            $table->string('email', 500);
            $table->string('impuesto', 500);

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('sucursals');
    }
}
