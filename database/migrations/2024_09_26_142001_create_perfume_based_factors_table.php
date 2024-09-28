<?php

use App\Models\Factor;
use App\Models\Perfume;
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
            $table->foreignIdFor(Perfume::class)->constrained();
            $table->string('name');
            $table->integer('volume');
            $table->decimal('price',8,0);
            $table->integer('stock')->default(0);
            $table->string('description')->nullable();
            $table->integer('sold')->default(0);
            //TODO if the sold is equal to quantity , we make is_active false or when seller wants to stop selling it
            $table->boolean('is_active')->default(true);
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
