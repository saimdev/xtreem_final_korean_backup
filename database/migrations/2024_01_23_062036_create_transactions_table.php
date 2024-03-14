<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('no');
            $table->integer('userno');
            $table->string('userid');
            $table->bigInteger('tid');
            $table->string('type');
            $table->decimal('amount', 18, 2);
            $table->decimal('before', 18, 2);
            $table->string('status');
            $table->string('gameid', 100);
            $table->string('gametype');
            $table->string('gameround', 100);
            $table->string('gametitle');
            $table->string('gamevendor');
            $table->string('detail');
            $table->tinyInteger('detailUpdate');
            $table->dateTime('created_at');
            $table->dateTime('updated_at');
            $table->dateTime('deleted_at');
            $table->dateTime('processed_at');
        });
    }

    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
