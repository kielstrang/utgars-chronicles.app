<?php declare(strict_types=1);

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemoveNullableFromHistoryIdColumns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('events', function (Blueprint $table) {
            $table->foreignId('history_id')->nullable(false)->change();
        });

        Schema::table('scenes', function (Blueprint $table) {
            $table->foreignId('history_id')->nullable(false)->change();
        });
    }
}
