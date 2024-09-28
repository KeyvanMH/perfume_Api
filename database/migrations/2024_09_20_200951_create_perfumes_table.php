<?php

use App\Models\Brand;
use App\Models\Category;
use App\Models\Discount;
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
        Schema::create('perfumes', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(Brand::class)->constrained();
            $table->foreignIdFor(Category::class)->constrained();
            $table->string('name');
            $table->decimal('price',8,0);
            $table->integer('volume');
            $table->integer('quantity')->default(0);
            $table->string('description');
            $table->string('slug')->unique();
            $table->string('warranty')->nullable();
            $table->enum('gender',['male','female','sport']);
            $table->boolean('is_active')->default(true);
            $table->integer('sold')->default(0);
            //discount section
            $table->decimal('discount_percent',4,2)->nullable();
            $table->decimal('discount_amount',11,2)->nullable();
            $table->timestamp('discount_start_date')->nullable();
            $table->timestamp('discount_end_date')->nullable();
            $table->string('discount_card')->nullable();
            $table->decimal('discount_card_percent',4,2)->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfumes');
    }
};
