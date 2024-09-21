<?php

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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            $table->decimal('percent',4,2);
            $table->decimal('amount',11,2)->nullable();
            $table->timestamp('start_date')->default(now());
            $table->timestamp('end_date');
            //TODO identifier for every perfume
            $table->enum('status', ['active', 'inactive', 'expired'])->default('active');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
