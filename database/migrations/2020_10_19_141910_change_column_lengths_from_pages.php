<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeColumnLengthsFromPages extends Migration
{
    public function up()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->longText('content_uk')->nullable()->change();
            $table->longText('content_ru')->nullable()->change();
        });
    }

    public function down()
    {
        Schema::table('pages', function (Blueprint $table) {
            $table->longText('content_uk')->nullable()->change();
            $table->longText('content_ru')->nullable()->change();
        });
    }
}
