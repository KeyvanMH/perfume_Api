<?php

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
        Schema::create('sold_factors', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(User::class)->nullable()->constrained()
                ->onDelete('set null');
            $table->string('transaction_id')->unique();
            $table->string('reference_id')->nullable();
            $table->enum('status', ['pending', 'verified', 'failed'])->default('pending');
            $table->string('shipping_price');
            $table->string('total_price_to_pay');
            $table->boolean('is_delivered')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sold_factors');
    }
};
