<?php

use App\Models\Perfume;
use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('perfume_solds', function (Blueprint $table) {
            //TODO make this morph for all products
            $table->id();
            $table->foreignIdFor(Perfume::class)->constrained()
                ->onDelete('restrict');
            //TODO check if it makes foreign id to null
            $table->foreignIdFor(User::class)->constrained()
                ->onDelete('set null');
            $table->integer('number');
            $table->integer('price');
            $table->integer('price_with_discount');
            $table->integer('final_price');
            $table->integer('delivery_price');
            $table->enum('is_delivered',[true,false]);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfume_solds');
    }
};
