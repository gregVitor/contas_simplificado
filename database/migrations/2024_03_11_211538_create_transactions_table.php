<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('bank_account_id');
            $table->unsignedBigInteger('origin_bank_account_id');
            $table->unsignedBigInteger('destiny_bank_account_id');

            $table->decimal('amount', 10, 2);
            $table->string('type');
            $table->text('description')->nullable();
            $table->timestamps();

            $table->foreign('bank_account_id')
                ->references('id')
                ->on('bank_accounts')
                ->onDelete('cascade');

            $table->foreign('origin_bank_account_id')
                ->references('id')
                ->on('bank_accounts')
                ->onDelete('cascade');

            $table->foreign('destiny_bank_account_id')
                ->references('id')
                ->on('bank_accounts')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('transactions');
    }
}
