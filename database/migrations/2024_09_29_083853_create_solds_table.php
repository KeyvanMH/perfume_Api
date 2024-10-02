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
        Schema::create('solds', function (Blueprint $table) {
            $table->id();
            $table->morphs('product');
            $table->foreignIdFor(User::class)->nullable()->constrained()
                ->onDelete('set null');
            $table->integer('number');
            $table->integer('price');
            $table->integer('price_with_discount');
            $table->integer('final_price');
            $table->integer('delivery_price');
            $table->boolean('is_delivered')->default(false);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('solds');
    }
};
