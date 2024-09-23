<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class CreateMobileAppAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mobile_app_assets', function (Blueprint $table) {
            $table->id();
            $table->string('logo')->default('logo.png');
            $table->string('chart_icon')->default('default.png');
            $table->string('article_icon')->default('default.png');
            $table->string('podcast_icon')->default('default.png');
            $table->string('article_page_icon')->default('default.png');
            $table->string('youtube_page_icon')->default('default.png');
            $table->string('location')->default('mnl');
            $table->timestamps();
        });

        /*DB::table('mobile_app_assets')->insert([
            'logo' => 'logo.png',
            'chart_icon' => 'charts.png',
            'article_icon' => 'articles.png',
            'podcast_icon' => 'morning-show.png',
            'article_page_icon' => 'articles.png',
            'youtube_page_icon' => 'youtube.png',
            'location' => 'mnl',
        ]);*/
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('mobile_app_assets');
    }
}
