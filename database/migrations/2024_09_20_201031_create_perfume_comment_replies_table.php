<?php

use App\Models\PerfumeComment;
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
        Schema::create('perfume_comment_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignIdFor(PerfumeComment::class)->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->foreignIdFor(User::class)->constrained()
                ->onDelete('cascade')
                ->onUpdate('cascade');
            $table->string('reply');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('perfume_comment_replies');
    }
};
