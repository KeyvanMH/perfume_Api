<?php

use App\Models\Factor;
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
        Schema::create('perfume_based_factors', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Factor::class)->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('name');
            $table->integer('volume');
            $table->decimal('price',8,0);
            $table->integer('quantity')->default(0);
            $table->string('description');
            $table->string('slug')->unique();
            $table->string('warranty')->nullable();
            $table->enum('gender',['male','female','sport']);
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfume_based_factors');
    }
};
