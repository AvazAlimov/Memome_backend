<?php /** @noinspection PhpUndefinedMethodInspection */

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemoriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('memories', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('account');
            $table->string('title');
            $table->text('content');
            $table->string('date')->nullable();
            $table->timestamps();

            $table->foreign('account')->references('id')->on('accounts')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('memories');
    }
}
